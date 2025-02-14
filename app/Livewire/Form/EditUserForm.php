<?php

namespace App\Livewire\Form;

use Livewire\Component;
use Livewire\Attributes\Rule;
use App\Models\User;

class EditUserForm extends Component
{
    public $userData = [];

    #[Rule('required|min:3|max:255')]
    public $name = '';

    #[Rule('required|email|max:255')]
    public $email = '';

    #[Rule('required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10')]
    public $phone = '';

    #[Rule('required|in:Active,Inactive,Pending')]
    public $status = '';

    #[Rule('required|numeric')]
    public $balance = 0;

    #[Rule('required|numeric')]
    public $deposit = 0;

    public function mount($userData)
    {
        $this->userData = $userData;
        $this->name = $userData['name'];
        $this->email = $userData['description'];
        $this->phone = $userData['phone'];
        $this->status = $userData['status'];
        $this->balance = $userData['balance'];
        $this->deposit = $userData['deposit'];
    }

    public function save()
    {
        $validated = $this->validate();

        try {
            $user = User::find($this->userData['user_id']);
            $user->update([
                'name' => $this->name,
                'email' => $this->email,
                'phone' => $this->phone,
                'status' => $this->status,
            ]);

            // Mise à jour des données dans le datatable
            $this->dispatch('user-updated', [
                'user_id' => $this->userData['user_id'],
                'name' => $this->name,
                'phone' => $this->phone,
                'description' => $this->email,
                'status' => $this->status,
                'balance' => $this->balance,
                'deposit' => $this->deposit,
            ]);

            $this->dispatch('toggle-modal');
            $this->dispatch('notify', [
                'message' => 'Utilisateur mis à jour avec succès',
                'type' => 'success'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'message' => 'Erreur lors de la mise à jour',
                'type' => 'error'
            ]);
        }
    }

    public function render()
    {
        return view('livewire.form.edit-user-form');
    }
}
