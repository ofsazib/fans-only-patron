<?php

namespace App\Http\Livewire;

use App\Subscription;
use Livewire\Component;
use Livewire\WithPagination;


class UserSubscribersList extends Component
{
	use WithPagination;

	public $tab;

	public function mount()
	{
		$this->tab = 'Free';
	}

	public function tab($tab)
	{
		$this->resetPage();
		$this->tab = $tab;
	}

	public function render()
	{

		if ($this->tab == 'Free') {

			$subscribers = auth()->user()->followers()
				->simplePaginate(opt('followListPerPage', 60));
		} elseif ($this->tab == 'Paid') {

			$subscribers = auth()->user()->subscribers()->with('subscriber')
				->where('subscription_expires', '>=', now())
				->simplePaginate(opt('followListPerPage', 60));
		}

		return view('livewire.user-subscribers-list')
			->with('subscribers', $subscribers);
	}

	public function unfollow($userId)
	{
		// toggle follow
		auth()->user()->toggleFollow($userId);
	}
}
