# livewire-datatable
figma design(https://www.figma.com/community/file/786976918221602322/data-table)


# LIVEWIRE 3 DISCOVERING - Livewire Cheat Sheet

# *Components*

### Création d'un composant
- `php artisan make:livewire [CreatePost|create-post]` : **kebab-case** ou **PascalCase**.
- `php artisan make:livewire [Posts\\CreatePost|posts.create-post]` : Précise le **namespace** (création dans un sous-dossier, ici `posts`).

### Propriété d'un composant
```php
namespace App\Livewire;

use Livewire\Component;

class CreatePost extends Component
{
    public $title = 'Post title...'; 
    
    public function render()
    {
        return view('livewire.create-post', [
            'author' => Auth::user()->name, 
        ]);
    }
}
```

### Accéder aux propriétés dans une vue
```blade
<!-- livewire.create-post -->
<div>
    <h1>Title: "{{ $title }}"</h1>
</div>
```

## `wire:key` : Gestion des éléments dans une boucle `foreach`
```blade
<div>
    @foreach ($posts as $post)
        <div wire:key="{{ $post->id }}"></div>
        @livewire(PostItem::class, ['post' => $post], key($post->id)) <!-- Si on utilise @livewire -->
    @endforeach
</div>
```

## `wire:model` : Synchronisation des données
- Lors d'une execution d'une action, ici c'est l'execution de submit
```blade
<form wire:submit.prevent="submit">
    <label for="title">Title:</label>
    <input type="text" id="title" wire:model="title"> 
</form>
```
```php
class Card extends Component
{
    public $title = 'title';
    
    public function submit()
    {
        dd($this->title); 
    }
}
```

### `wire:model.live` :
- Synchronisation en temps réel (utile pour des validations en direct).

## Passer des données à un composant
```blade
<livewire:create-post title="Initial Title" /> <!-- Valeur statique -->
<livewire:create-post :title="$title" /> <!-- Valeur dynamique -->
```
- Si `$title` est modifié **après** le chargement du composant, **la nouvelle valeur ne sera pas transmise**.

### `mount` : Hook pour initialiser les valeurs des propriétés
```php
public function mount($title, $description)
{
    $this->description = strtoupper($title);
    $this->title = $description;
}
```

## Full-page components
- Associe un composant Livewire à une route pour créer une page complète.
```php
Route::get('/posts/create', CreatePost::class); 
```

### Le layout
```bash
php artisan livewire:layout # Création du fichier resources/views/components/layouts/app.blade.php
```

```blade
<!-- resources/views/components/layouts/app.blade.php -->
<html>
    <head>
        <title>{{ $title ?? 'Page Title' }}</title>
    </head>
    <body>
        {{ $slot }} <!-- Contenu de la page -->
    </body>
</html>
```

### Configuration globale
```php

'layout' => 'layouts.app', 
```

### Définir un layout pour un composant full-page
1. Au-dessus de `render()`
```php
class CreatePost extends Component
{
    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.create-post');
    }
}
```

2. Au-dessus de la déclaration de la classe
```php
#[Layout('layouts.app')] 
class CreatePost extends Component
```

3. Méthode `layout()`
```php
public function render()
{
    return view('livewire.create-post')->layout('layouts.app');
}
```

4. Utilisation traditionnelle de Blade avec `@extends`
```blade
<body>
    @yield('content')
</body>
```

```php
public function render()
{
    return view('livewire.show-posts')->extends('layouts.app');
}
```

#### `#[Title('Titre de la page')]` : Définir le titre de la page
1. Déclaration au-dessus de `render()` ou `class`
```php
#[Title('Create Post')]
public function render()
{
    return view('livewire.create-post');
}
```
2. Utilisation de la méthode `title()`
```php
return view('livewire.create-post')->title('Create Post');
```

#### Accéder aux paramètres de la route
```php
class ShowPost extends Component
{
    public Post $post; 
    
    public function mount($id)
    {
        $this->post = Post::findOrFail($id); 
    }

    public function render()
    {
        return view('livewire.show-post');
    }
}
```

## Les composants imbriqués
### Résumé
- Livewire ne supporte pas <livewire:component1><livewire:component2></livewire:component1>
- Tu dois inclure les enfants directement dans le fichier Blade du parent
- Utilise :data="$variable" pour passer des données aux enfants

### **1. Imbrication des composants**  
Un composant Livewire peut être imbriqué dans un autre en l'incluant dans la vue Blade du parent. Exemple :  
```php
<livewire:todo-list />
```
Le composant enfant est indépendant après le premier rendu.  

### **2. Passage de données aux enfants**  
Les données du parent sont passées via la syntaxe `:prop="$value"`. Exemple :  
```php
<livewire:todo-count :todos="$todos" />
```
Le composant enfant reçoit la donnée via `mount()`, ou directement via une propriété publique si les noms correspondent.  

### **3. Props statiques et raccourcis**  
Les valeurs statiques peuvent être passées sans `:` :  
```php
<livewire:todo-count label="Todo Count:" />
```
Raccourci pour éviter la répétition du nom de variable :  
```php
<livewire:todo-count :$todos />
```

### **4. Rendu dans une boucle**  
Chaque composant dans une boucle doit avoir une clé unique :  
```php
<livewire:todo-item :$todo :key="$todo->id" />
```
Livewire dépend fortement des clés pour le suivi du rendu.  

### **5. Props réactives**  
Par défaut, les props ne sont pas réactives. Pour les rendre réactives, utiliser l’attribut `#[Reactive]` :  
```php
#[Reactive] 
public $todos;
```
Cela met automatiquement à jour le composant enfant lors des modifications du parent.  

### **6. Binding avec `wire:model`**  
Permet au parent de synchroniser une valeur avec un enfant :  
```php
<livewire:todo-input wire:model="todo" />
```
L'enfant doit utiliser l’attribut `#[Modelable]` pour activer cette liaison.  

### **7. Écoute d’événements**  
Un parent peut écouter un événement émis par un enfant via `#[On]` :  
```php
#[On('remove-todo')] 
public function remove($todoId) { /* ... */ }
```
L'enfant déclenche l’événement avec :  
```php
$this->dispatch('remove-todo', todoId: $this->todo->id);
```

### **8. Optimisation des performances**  
Pour éviter un aller-retour réseau inutile, privilégier `$dispatch` côté client :  
```html
<button wire:click="$dispatch('remove-todo', { todoId: {{ $todo->id }} })">Remove</button>
```

### **9. Accès direct au parent**  
Un enfant peut appeler une méthode du parent via `$parent` :  
```html
<button wire:click="$parent.remove({{ $todo->id }})">Remove</button>
```

### **10. Composants dynamiques**  
Le composant enfant peut être déterminé dynamiquement avec :  
```php
<livewire:dynamic-component :is="$componentName" />
```

# **Properties**

## Initialisation des Propriétés

...-

## Affectation en Masse

```php
class UpdatePost extends Component
{
    public $post;
    public $title;
    public $description;

    public function mount(Post $post)
    {
        $this->post = $post;
        
        
        $this->fill($post->only('title', 'description'));
    }
}
```

## `reset()`: Réinitialisation des Propriétés

- `` réinitialise une propriété à sa **valeur initiale** (avant `mount()`), et non à la valeur définie dans `mount()`.

### Exemple :

```php
//...
    public $todos = [];
    public $todo = '';  

    public function mount()
    {
        $this->todo = 'Initial Todo';  
    }

    public function addTodo()
    {
        $this->todos[] = $this->todo;
        $this->reset('todo');  
    }
//...
```

### Comportement :

- remet la valeur à son état **avant `mount()`**.
- Pour réinitialiser à la valeur définie dans `mount()`, fais-le manuellement :

```php
$this->todo = 'Initial Todo';  
```

## `$this->pull('todo')`

- Fonctionne comme `reset()`, mais **retourne l'ancienne valeur avant réinitialisation**.

```php
$this->todos[] = $this->pull('todo'); 
```

## Types Supportés par Livewire

### Types Primitifs

- `array`, `string`, `int`, `float`, `bool`, `null`

### Types Laravel

> Ces types sont **désérialisés en JSON** et **reconvertis en PHP** à chaque requête.

- `BackedEnum`
- `Illuminate\Support\Collection`
- `Illuminate\Database\Eloquent\Collection`
- `Illuminate\Database\Eloquent\Model`
- `DateTime`
- `Carbon\Carbon`
- `Illuminate\Support\Stringable`

### Types Personnalisés

- Utilisation de `Wireables` et `Synthesizers`

## Accès aux Propriétés via JavaScript

### Récupération

- `$wire.propertyName`
- `$wire.get('propertyName')`

### Manipulation

- `$wire.todo = nouvelleValeur` *(pas de synchronisation serveur)*
- `$wire.set('todo', nouvelleValeur, true/false)` *(synchronisation si **``**)*

## Sécurité

Il est essentiel de valider et d'autoriser les propriétés avant de les enregistrer dans la base de données, comme pour une requête de contrôleur.

### Sécuriser les Propriétés

- `` : Rend une propriété en lecture seule (utile pour verrouiller un `id`).
- `` avant modification :
  ```php
  public function update()
  {
      $post = Post::findOrFail($this->id);    
      $this->authorize('update', $post); 
      $post->update(...);
  }
  ```
- \*\*Utilisation d'un \*\*`` : Livewire verrouille automatiquement l'`id` pour éviter sa modification.
  ```php
  class UpdatePost extends Component
  {
      public Post $post;
      public $title;
      public $content;

      public function mount(Post $post)
      {
          $this->post = $post;
          $this->title = $post->title;
          $this->content = $post->content;
      }

      public function update()
      {
          $this->post->update([
              'title' => $this->title,
              'content' => $this->content,
          ]);
      }
  ```

### Exposition des Éléments Sensibles

#### Nom de classe exposé

- Solution : Utiliser `Relation::morphMap()` pour un alias dans une relation polymorphique.

```json
{
    "type": "model",
    "class": "App\Models\Post", 
    "key": 1,
    "relationships": []
}
```

```php

namespace App\Providers;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Relation::morphMap([
            'post' => 'App\Models\Post', 
        ]);
    }
}
```

### Contraintes Eloquent non conservées entre requêtes dans Livewire [?]

...

## `#[Computed]`: Propriété Calculée

- Une propriété `` est une méthode **mise en cache durant la requête**, évitant un recalcul inutile. Elle est accessible comme une propriété publique.

### Déclaration

```php
use Livewire\Attributes\Computed;
//..
    public $userId;

    #[Computed]
    public function user()
    {
        return User::find($this->userId);
    }
//..
```

### Utilisation dans Blade

Accès à la propriété calculée via `$this` :

```blade
<div>
    <h1>{{ $this->user->name }}</h1>
</div>
```

### **Avantages de **`computed`** en Livewire**

1. **Optimisation des performances** : mise en cache des valeurs pour éviter des appels inutiles.
2. **Accès aux données dérivées** : permet de générer des valeurs basées sur d'autres propriétés.
3. **Simplification du rendu Blade** : accessible directement dans le template via `$this->property`.
4. **Busting du cache** : possibilité d'invalider le cache avec `unset($this->property)`.
5. **Cache persistant entre requêtes** : `#[Computed(persist: true)]` conserve la valeur jusqu'à expiration.
6. **Cache global entre composants** : `#[Computed(cache: true)]` partage une valeur en cache entre plusieurs composants.
7. **Conditionnement du chargement des données** : exécute une requête seulement si elle est nécessaire.
8. **Utilisation dans les templates inline** : permet d’accéder aux données sans méthode `render()`.
9. **Réduction du boilerplate** : supprime le besoin de passer des données via `render()`.


# **Actions**

## `$refresh | $commit` : Rafraîchissement d'un composant  

```blade
<button type="button" wire:click="$refresh">...</button>
```

- L'exécution de `$refresh` déclenche les hooks **`hydrate`**, **`dehydrate`** et **`render`**.  
- Pour rafraîchir un composant via AlpineJS : **`$wire.$refresh()`**.
- Lorsqu'un composant est rafraîchi, **les valeurs des propriétés mises à jour via `wire:model` sont envoyées au backend, mais ne sont pas automatiquement reflétées dans la vue côté frontend**. Par défaut, les valeurs mises à jour via `wire:model` ne sont pas immédiatement visibles dans la vue jusqu'à ce qu'une synchronisation ou un rechargement de la vue soit effectué.

## Event Listeners
- `wire:*`, avec * le nom de l'evenement. Supporte les evenements javascripts.

### Écouter des touches spécifiques
- `wire:keydown.[enter|shift|enter|space|ctrl|cmd|meta|alt|up|down|left|right|escape|tab|caps-lock|equal|period|slash]`

### Event handler modifiers (https://livewire.laravel.com/docs/actions#event-handler-modifiers)
```blade
<input wire:keydown.[prevent|...]="...">
```
...
