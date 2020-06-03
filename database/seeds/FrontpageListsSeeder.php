<?php

use Illuminate\Database\Seeder;
use App\User;
use App\TList;
use App\Task;
use App\FrontpageList;

class FrontpageListsSeeder extends Seeder
{
		protected function makeUserFrontpageList($username, $list_name, $list_items)
		{
				$user = factory(User::class)->create(['name'=>$username]);
				$list = factory(TList::class)->states('public')->create(['user_id'=>$user, 'name'=>$list_name]);

				foreach ($list_items as $task_name => $done) {
					$factory = factory(Task::class);
					if ($done) $factory->states('completed');
					$factory->create(['name'=>$task_name, 'user_id' => $user, 'list_id' => $list]);
				}

				$frontpage = new FrontpageList;
				$frontpage->list()->associate($list);
				$frontpage->save();
		}

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->makeUserFrontpageList('Joe Exotic', 'Animals to pet when I get out of prison',
	        													 ['White Tiger'=>true,
	        													 	'Snow Leopard'=>false,
		        													'Black Tiger'=>true,
		        													'Pink Amazonian River Dolphin'=>false,
		        													'Panther'=>true,
		        													'Rattlesnake'=>false]);

        $this->makeUserFrontpageList('The Queen', 'Animals One wishes to pet',
	        													 ['Corgie'=>true,
	        													 	'Corgie Pomeranian Cross'=>true,
		        													'Corgie Shiba Inu Cross'=>false,
		        													'Corgie Golden Retriever Cross'=>true,
		        													'Corgie Siberian Huskie Cross'=>true,
		        													'Corgie Dalmatian Cross'=>false,
		        													'Corgie Miniature Schnauzer Cross'=>false]);

        $this->makeUserFrontpageList('Tom Hanks', 'Animals to pet in Australia',
	        													 ['Kangaroo'=>false,
	        													 	'Koala'=>true,
		        													'Tasmanian Devil'=>false,
		        													'Camel'=>true,
		        													'Horse'=>true,
		        													'Crocodile'=>false]);
    }
}
