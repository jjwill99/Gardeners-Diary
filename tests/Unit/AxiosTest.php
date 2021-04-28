<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;
use App\User;
use App\Garden;
use App\Plant;
use App\PlantLocation;
use App\PlantActivity;
use App\GardenHistory;
use App\PlantHistory;
use App\PlantLocationHistory;

class AxiosTest extends TestCase
{
    use RefreshDatabase;

    public function testGetGardens()
    {
        $user = factory(User::class)->create();
        $first_garden = factory(Garden::class)->create(['user_id' => $user->id]);
        $second_garden = factory(Garden::class)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'web')->json('GET', '/api/getGardens');
        $array = json_decode($response->getContent());

        $this->assertCount(2, $array);
        $response->assertStatus(200);

        $test = $first_garden;
        foreach ($array as $garden) {
            $this->assertEquals($test->id, $garden->id);
            $this->assertEquals($test->user_id, $garden->user_id);
            $this->assertEquals($test->name, $garden->name);
            $this->assertEquals($test->width, $garden->width);
            $this->assertEquals($test->length, $garden->length);
            $this->assertEquals($test->grid, $garden->grid);
            $this->assertEquals($test->picture, $garden->picture);
            
            $test = $second_garden;
        }
    }

    public function testDeleteGarden()
    {
        $user = factory(User::class)->create();
        $garden = factory(Garden::class)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'web')->json('POST', '/api/deleteGarden', ['id' => $garden->id]);
        
        $this->assertDeleted($garden);        
        $response->assertStatus(302);
    }

    public function testAddGarden()
    {
        $user = factory(User::class)->create();
        $garden = factory(Garden::class)->create(['user_id' => $user->id]);
        $data = [
            'name' => "Home",
            'width' => '5',
            'picture' => 'null',
            'length' => '10'
        ];

        $response = $this->actingAs($user, 'web')->json('POST', '/api/addGarden', $data);
        $response->assertStatus(302);

        $array = json_decode($this->actingAs($user, 'web')->json('GET', '/api/getGardens')->getContent());
        $this->assertCount(2, $array);
    }

    public function testCheckGardenUser()
    {
        $user = factory(User::class)->create();
        $unauthorised_user = factory(User::class)->create();

        $garden = factory(Garden::class)->create(['user_id' => $user->id]);

        // Checking authorised user is validated
        $response = $this->actingAs($user, 'web')->json('GET', '/api/checkGardenUser', ['gardenId' => $garden->id]);
        $value = json_decode($response->getContent())->value;

        $this->assertTrue($value);
        $response->assertStatus(200);

        // Checking unauthorised user is not validated
        $response = $this->actingAs($unauthorised_user, 'web')->json('GET', '/api/checkGardenUser', ['gardenId' => $garden->id]);
        $value = json_decode($response->getContent())->value;

        $this->assertFalse($value);
        $response->assertStatus(200);
    }

    public function testGetGarden()
    {
        $user = factory(User::class)->create();
        $garden = factory(Garden::class)->create(['user_id' => $user->id]);

        $response = $this->actingAs($user, 'web')->json('GET', '/api/getGarden', ['gardenId' => $garden->id]);
        $array = json_decode($response->getContent());

        $this->assertEquals($garden->id, $array->id);
        $this->assertEquals($garden->user_id, $array->user_id);
        $this->assertEquals($garden->name, $array->name);
        $this->assertEquals($garden->width, $array->width);
        $this->assertEquals($garden->length, $array->length);
        $this->assertEquals($garden->grid, $array->grid);
        $this->assertEquals($garden->picture, $array->picture);
        $response->assertStatus(200);
    }

    public function testGetActivities()
    {
        $user = factory(User::class)->create();
        $garden = factory(Garden::class)->create(['user_id' => $user->id]);
        $plant = factory(Plant::class)->create(['garden_id' => $garden->id]);
        $activity1 = factory(PlantActivity::class)->create(['plant_id' => $plant->id]);
        $activity2 = factory(PlantActivity::class)->create(['plant_id' => $plant->id]);

        $response = $this->actingAs($user, 'web')->json('GET', '/api/getActivities', ['id' => $plant->id, 'gardenId' => $garden->id]);
        $array = json_decode($response->getContent());

        $this->assertCount(2, $array);

        $test = $activity1;
        foreach ($array as $activity) {
            $this->assertEquals($test->id, $activity->id);
            $this->assertEquals($test->name, $activity->name);
            $this->assertEquals($test->description, $activity->description);
            $this->assertEquals($test->time, $activity->time);
            $this->assertEquals($test->completed, $activity->completed);
            $this->assertEquals($test->frequency, $activity->frequency);
            $this->assertEquals($test->plant_id, $activity->plant_id);

            $test = $activity2;
        }
        
        $response->assertStatus(200);
    }

    public function testGetHistories()
    {
        $user = factory(User::class)->create();
        $garden = factory(Garden::class)->create(['user_id' => $user->id]);
        $gardenHistory = factory(GardenHistory::class)->create(['garden_id' => $garden->id]);

        $response = $this->actingAs($user, 'web')->json('GET', '/api/getHistories', ['gardenId' => $garden->id]);
        $array = json_decode($response->getContent())[0];

        $this->assertEquals($gardenHistory->id, $array->id);
        $this->assertEquals($gardenHistory->name, $array->name);
        $this->assertEquals($gardenHistory->width, $array->width);
        $this->assertEquals($gardenHistory->length, $array->length);
        $this->assertEquals($gardenHistory->grid, $array->grid);
        $this->assertEquals($gardenHistory->date, $array->date);
        $this->assertEquals($gardenHistory->garden_id, $array->garden_id);

        $response->assertStatus(200);
    }

    public function testAddActivity()
    {
        $user = factory(User::class)->create();
        $garden = factory(Garden::class)->create(['user_id' => $user->id]);
        $plant = factory(Plant::class)->create(['garden_id' => $garden->id]);

        $name = 'Water roses';
        $desc = 'Use watering can';
        $time = now();
        $completed = '0';
        $freq = 'null';

        $data = [
            'name' => $name,
            'description' => $desc,
            'time' => $time,
            'completed' => $completed,
            'plantId' => $plant->id,
            'frequency' => $freq,
        ];

        $response = $this->actingAs($user, 'web')->json('POST', '/api/addActivity', $data);
        $response->assertStatus(302);
        
        $array = json_decode($this->actingAs($user, 'web')->json('GET', '/api/getActivities', ['id' => $plant->id, 'gardenId' => $garden->id])->getContent())[0];
        $newTime = substr($array->time, 0, 10) . " " . substr($array->time, 11, 8);

        $this->assertEquals($name, $array->name);
        $this->assertEquals($desc, $array->description);
        $this->assertEquals($time, $newTime);
        $this->assertEquals($completed, $array->completed);
        $this->assertEquals($plant->id, $array->plant_id);
        $this->assertEquals($freq, $array->frequency);
    }

    public function testCompleteActivity()
    {
        // Check one time activity
        $user = factory(User::class)->create();
        $garden = factory(Garden::class)->create(['user_id' => $user->id]);
        $plant = factory(Plant::class)->create(['garden_id' => $garden->id]);

        $name = 'Water roses';
        $desc = 'Use watering can';
        $time = now();
        $completed = '0';
        $freq = 'null';

        $data = [
            'name' => $name,
            'description' => $desc,
            'time' => $time,
            'completed' => $completed,
            'plantId' => $plant->id,
            'frequency' => $freq,
        ];

        $this->actingAs($user, 'web')->json('POST', '/api/addActivity', $data);
        $activity = json_decode($this->actingAs($user, 'web')->json('GET', '/api/getActivities', ['id' => $plant->id, 'gardenId' => $garden->id])->getContent())[0];

        $this->assertEquals('0', $activity->completed);

        $response = $this->actingAs($user, 'web')->json('POST', '/api/completeActivity', ['id' => $activity->id]);
        $refreshedActivity = json_decode($this->actingAs($user, 'web')->json('GET', '/api/getActivities', ['id' => $plant->id, 'gardenId' => $garden->id])->getContent())[0];

        $this->assertEquals('1', $refreshedActivity->completed);

        $response->assertStatus(200);

        // Check repeating activity
        $user = factory(User::class)->create();
        $garden = factory(Garden::class)->create(['user_id' => $user->id]);
        $plant = factory(Plant::class)->create(['garden_id' => $garden->id]);

        $name = 'Trim roses';
        $desc = 'Use scissors';
        $time = now();
        $completed = '0';
        $freq = '1 day';

        $data = [
            'name' => $name,
            'description' => $desc,
            'time' => $time,
            'completed' => $completed,
            'plantId' => $plant->id,
            'frequency' => $freq,
        ];

        $this->actingAs($user, 'web')->json('POST', '/api/addActivity', $data);
        $activity = json_decode($this->actingAs($user, 'web')->json('GET', '/api/getActivities', ['id' => $plant->id, 'gardenId' => $garden->id])->getContent())[0];

        $this->assertEquals('0', $activity->completed);

        $response = $this->actingAs($user, 'web')->json('POST', '/api/completeActivity', ['id' => $activity->id]);
        $response->assertStatus(200);

        $refreshedActivity = json_decode($this->actingAs($user, 'web')->json('GET', '/api/getActivities', ['id' => $plant->id, 'gardenId' => $garden->id])->getContent())[0];
        $this->assertEquals('1', $refreshedActivity->completed);

        $newActivity = json_decode($this->actingAs($user, 'web')->json('GET', '/api/getActivities', ['id' => $plant->id, 'gardenId' => $garden->id])->getContent())[1];
        $newTime = substr($newActivity->time, 0, 10) . " " . substr($newActivity->time, 11, 8);

        $this->assertEquals($name, $newActivity->name);
        $this->assertEquals($desc, $newActivity->description);
        $this->assertEquals(date('Y-m-d H:i:s', strtotime($time . ' +1 day')), $newTime);
        $this->assertEquals('0', $newActivity->completed);
        $this->assertEquals($freq, $newActivity->frequency);
    }

    public function testUpdateActivity()
    {
        $user = factory(User::class)->create();
        $garden = factory(Garden::class)->create(['user_id' => $user->id]);
        $plant = factory(Plant::class)->create(['garden_id' => $garden->id]);

        $name = 'Water roses';
        $desc = 'Use watering can';
        $time = now();
        $completed = '0';
        $freq = 'null';

        $data = [
            'name' => $name,
            'description' => $desc,
            'time' => $time,
            'completed' => $completed,
            'plantId' => $plant->id,
            'frequency' => $freq,
        ];

        $this->actingAs($user, 'web')->json('POST', '/api/addActivity', $data);
        $activity = json_decode($this->actingAs($user, 'web')->json('GET', '/api/getActivities', ['id' => $plant->id, 'gardenId' => $garden->id])->getContent())[0];

        $name = 'Water roses again';
        $desc = 'Use watering can again';
        $time = now();
        $freq = '1 day';

        $data = [
            'name' => $name,
            'description' => $desc,
            'time' => $time,
            'activityId' => $activity->id,
            'frequency' => $freq,
        ];

        $response = $this->actingAs($user, 'web')->json('POST', '/api/updateActivity', $data);
        $newActivity = json_decode($this->actingAs($user, 'web')->json('GET', '/api/getActivities', ['id' => $plant->id, 'gardenId' => $garden->id])->getContent())[0];
        $newTime = substr($newActivity->time, 0, 10) . " " . substr($newActivity->time, 11, 8);

        $this->assertEquals($name, $newActivity->name);
        $this->assertEquals($desc, $newActivity->description);
        $this->assertEquals($time, $newTime);
        $this->assertEquals('0', $newActivity->completed);
        $this->assertEquals($freq, $newActivity->frequency);

        $response->assertStatus(302);
    }

    public function testDeleteActivity()
    {
        $user = factory(User::class)->create();
        $garden = factory(Garden::class)->create(['user_id' => $user->id]);
        $plant = factory(Plant::class)->create(['garden_id' => $garden->id]);

        $name = 'Water roses';
        $desc = 'Use watering can';
        $time = now();
        $completed = '0';
        $freq = '1 day';

        $data = [
            'name' => $name,
            'description' => $desc,
            'time' => $time,
            'completed' => $completed,
            'plantId' => $plant->id,
            'frequency' => $freq,
        ];

        $this->actingAs($user, 'web')->json('POST', '/api/addActivity', $data);
        $array = json_decode($this->actingAs($user, 'web')->json('GET', '/api/getActivities', ['id' => $plant->id, 'gardenId' => $garden->id])->getContent());
        $newTime = substr($array[0]->time, 0, 10) . " " . substr($array[0]->time, 11, 8);

        $this->assertCount(1, $array);
        $this->assertEquals($time, $newTime);

        $response = $this->actingAs($user, 'web')->json('POST', '/api/deleteActivity', ['allOccurrences' => false, 'id' => $array[0]->id]);
        $array = json_decode($this->actingAs($user, 'web')->json('GET', '/api/getActivities', ['id' => $plant->id, 'gardenId' => $garden->id])->getContent());
        $newTime = substr($array[0]->time, 0, 10) . " " . substr($array[0]->time, 11, 8);

        $this->assertCount(1, $array);
        $this->assertNotEquals($time, $newTime);
        $response->assertStatus(302);

        $response = $this->actingAs($user, 'web')->json('POST', '/api/deleteActivity', ['allOccurrences' => true, 'id' => $array[0]->id]);
        $array = json_decode($this->actingAs($user, 'web')->json('GET', '/api/getActivities', ['id' => $plant->id, 'gardenId' => $garden->id])->getContent());

        $this->assertCount(0, $array);
        $response->assertStatus(302);
    }

    public function testStoreHistory()
    {
        $user = factory(User::class)->create();
        $garden = factory(Garden::class)->create(['user_id' => $user->id]);
        $plant = factory(Plant::class)->create(['garden_id' => $garden->id]);
        $plantLocation = factory(PlantLocation::class)->create(['plant_id' => $plant->id]);

        $date = '2021-04-01';
        $name = 'First moved in';

        $data = [
            'date' => $date,
            'gardenId' => $garden->id,
            'historyName' => $name,
        ];

        $response = $this->actingAs($user, 'web')->json('POST', '/api/storeHistory', $data);
        $response->assertStatus(200);

        // Check garden_histories table
        $response = $this->actingAs($user, 'web')->json('GET', '/api/getHistories', ['gardenId' => $garden->id]);
        $history = json_decode($response->getContent())[0];
        $newDate = substr($history->date, 0, 10);

        $this->assertEquals($name, $history->name);
        $this->assertEquals($garden->width, $history->width);
        $this->assertEquals($garden->length, $history->length);
        $this->assertEquals($garden->grid, $history->grid);
        $this->assertEquals($date, $newDate);
        $this->assertEquals($garden->id, $history->garden_id);

        // Check plant_histories table
        $response = $this->actingAs($user, 'web')->json('GET', '/api/getPlantHistory', ['historyId' => $history->id]);
        $plantHistory = json_decode($response->getContent())[0];
        $response->assertStatus(200);

        $this->assertEquals($plant->plant_name, $plantHistory->plant_name);
        $this->assertEquals($plant->icon, $plantHistory->icon);

        // Check plant_location_histories table
        $response = $this->actingAs($user, 'web')->json('GET', '/api/getPlantLocationHistory', ['historyId' => $history->id]);
        $plantLocationHistory = json_decode($response->getContent())[0];
        $response->assertStatus(200);

        $this->assertEquals($plantLocation->row, $plantLocationHistory->row);
        $this->assertEquals($plantLocation->column, $plantLocationHistory->column);
        $this->assertEquals($plantLocation->icon_location, $plantLocationHistory->icon_location);
        $this->assertEquals($plant->icon, $plantLocationHistory->icon);
        $this->assertEquals($plantHistory->id, $plantLocationHistory->plant_history_id);
        $this->assertEquals($plant->plant_name, $plantLocationHistory->plant_name);
    }

    public function testDeleteHistory()
    {
        $user = factory(User::class)->create();
        $garden = factory(Garden::class)->create(['user_id' => $user->id]);
        $plant = factory(Plant::class)->create(['garden_id' => $garden->id]);
        $plantLocation = factory(PlantLocation::class)->create(['plant_id' => $plant->id]);

        $date = '2021-04-01';
        $name = 'First moved in';

        $data = [
            'date' => $date,
            'gardenId' => $garden->id,
            'historyName' => $name,
        ];

        $this->actingAs($user, 'web')->json('POST', '/api/storeHistory', $data);
        $history = json_decode($this->actingAs($user, 'web')->json('GET', '/api/getHistories', ['gardenId' => $garden->id])->getContent())[0];

        $response = $this->actingAs($user, 'web')->json('POST', '/api/deleteHistory', ['id' => $history->id]);
        $response->assertStatus(302);

        $histories = json_decode($this->actingAs($user, 'web')->json('GET', '/api/getHistories', ['gardenId' => $garden->id])->getContent());
        $this->assertCount(0, $histories);

        $plants = json_decode($this->actingAs($user, 'web')->json('GET', '/api/getPlantHistory', ['historyId' => $history->id])->getContent());
        $this->assertCount(0, $plants);

        $locations = json_decode($this->actingAs($user, 'web')->json('GET', '/api/getPlantLocationHistory', ['historyId' => $history->id])->getContent());
        $this->assertCount(0, $locations);
    }

    public function testUpdateGarden()
    {
        $user = factory(User::class)->create();
        $garden = factory(Garden::class)->create(['user_id' => $user->id]);
        $plant = factory(Plant::class)->create(['garden_id' => $garden->id]);
        $plantLocation = factory(PlantLocation::class)->create(['plant_id' => $plant->id]);

        $col = $plantLocation->column == 0 ? 1 : 0;
        $iconLocation = '1030';

        $locations = [
            'row' => 0,
            'column' => $col,
            'icon_location' => $iconLocation,
            'icon' => $plant->icon,
        ];

        $grid = '[{"row":0, "colour":"saddleBrown", "column":0},{"row":0, "colour":"saddleBrown", "column":1}]';

        $data = [
            'grid' => $grid,
            'gardenId' => $garden->id,
            'locations' => json_encode([$locations]),
        ];

        $response = $this->actingAs($user, 'web')->json('POST', '/api/updateGarden', $data);
        $response->assertStatus(302);
        
        $newGarden = json_decode($this->actingAs($user, 'web')->json('GET', '/api/getGarden', ['gardenId' => $garden->id])->getContent());
        $this->assertEquals($grid, $newGarden->grid);

        $location = json_decode($this->actingAs($user, 'web')->json('GET', '/api/getPlantLocations', ['gardenId' => $garden->id])->getContent())[0];
        $this->assertEquals($iconLocation, $location->icon_location);
    }

    public function testAddPlant()
    {
        $user = factory(User::class)->create();
        $garden = factory(Garden::class)->create(['user_id' => $user->id]);
        $plant = factory(Plant::class)->create(['garden_id' => $garden->id]);

        $data = [
            'name' => 'Rose',
            'gardenId' => $garden->id,
            'icon' => UploadedFile::fake()->create('test.png', $kilobytes = 0),
        ];

        $plants = json_decode($this->actingAs($user, 'web')->json('GET', '/api/getPlants', ['gardenId' => $garden->id])->getContent());
        $this->assertCount(1, $plants);

        $response = $this->actingAs($user, 'web')->json('POST', '/api/addPlant', $data);
        $response->assertStatus(302);

        $plants = json_decode($this->actingAs($user, 'web')->json('GET', '/api/getPlants', ['gardenId' => $garden->id])->getContent());
        $this->assertCount(2, $plants);
        $this->assertEquals('Rose', $plants[1]->plant_name);
        $this->assertEquals('test.png', substr($plants[1]->icon, 0, 4).substr($plants[1]->icon, 15));
    }

    public function testGetPlants()
    {
        $user = factory(User::class)->create();
        $garden = factory(Garden::class)->create(['user_id' => $user->id]);
        $plant1 = factory(Plant::class)->create(['garden_id' => $garden->id]);
        $plant2 = factory(Plant::class)->create(['garden_id' => $garden->id]);

        $response = $this->actingAs($user, 'web')->json('GET', '/api/getPlants', ['gardenId' => $garden->id]);
        $plants = json_decode($response->getContent());
        
        $response->assertStatus(200);
        $this->assertCount(2, $plants);

        $test = $plant1;
        foreach ($plants as $plant) {
            $this->assertEquals($test->id, $plant->id);
            $this->assertEquals($test->plant_name, $plant->plant_name);
            $this->assertEquals($test->icon, $plant->icon);
            
            $test = $plant2;
        }
    }

    public function testGetPlantLocations()
    {
        $user = factory(User::class)->create();
        $garden = factory(Garden::class)->create(['user_id' => $user->id]);
        $plant = factory(Plant::class)->create(['garden_id' => $garden->id]);
        $plantLocation = factory(PlantLocation::class)->create(['plant_id' => $plant->id]);

        $response = $this->actingAs($user, 'web')->json('GET', '/api/getPlantLocations', ['gardenId' => $garden->id]);
        $location = json_decode($response->getContent())[0];
        
        $response->assertStatus(200);
        $this->assertEquals($location->id, $plantLocation->id);
        $this->assertEquals($location->row, $plantLocation->row);
        $this->assertEquals($location->column, $plantLocation->column);
        $this->assertEquals($location->icon_location, $plantLocation->icon_location);
        $this->assertEquals($location->plant_id, $plantLocation->plant_id);
    }

    public function testDeletePlant()
    {
        $user = factory(User::class)->create();
        $garden = factory(Garden::class)->create(['user_id' => $user->id]);
        $plant = factory(Plant::class)->create(['garden_id' => $garden->id]);

        $plants = json_decode($this->actingAs($user, 'web')->json('GET', '/api/getPlants', ['gardenId' => $garden->id])->getContent());
        $this->assertCount(1, $plants);

        $response = $this->actingAs($user, 'web')->json('POST', '/api/deletePlant', ['id' => $plant->id]);
        $response->assertStatus(302);

        $plants = json_decode($this->actingAs($user, 'web')->json('GET', '/api/getPlants', ['gardenId' => $garden->id])->getContent());
        $this->assertCount(0, $plants);
    }

    public function testCheckHistoryUser()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $garden = factory(Garden::class)->create(['user_id' => $user1->id]);
        $history = factory(GardenHistory::class)->create(['garden_id' => $garden->id]);

        // Check authorised user
        $response = $this->actingAs($user1, 'web')->json('GET', '/api/checkHistoryUser', ['historyId' => $history->id]);
        $value = json_decode($response->getContent());

        $response->assertStatus(200);
        $this->assertTrue($value->value);

        // Check unauthorised user
        $response = $this->actingAs($user2, 'web')->json('GET', '/api/checkHistoryUser', ['historyId' => $history->id]);
        $value = json_decode($response->getContent());

        $response->assertStatus(200);
        $this->assertFalse($value->value);
    }

    public function testGetGardenHistory()
    {
        $user = factory(User::class)->create();
        $garden = factory(Garden::class)->create(['user_id' => $user->id]);
        $history = factory(GardenHistory::class)->create(['garden_id' => $garden->id]);

        $response = $this->actingAs($user, 'web')->json('GET', '/api/getGardenHistory', ['historyId' => $history->id]);
        $newHistory = json_decode($response->getContent());

        $response->assertStatus(200);
        $this->assertEquals($history->id, $newHistory->id);
        $this->assertEquals($history->name, $newHistory->name);
        $this->assertEquals($history->width, $newHistory->width);
        $this->assertEquals($history->length, $newHistory->length);
        $this->assertEquals($history->grid, $newHistory->grid);
        $this->assertEquals($history->date, $newHistory->date);
        $this->assertEquals($history->garden_id, $newHistory->garden_id);
    }

    public function testGetPlantHistory()
    {
        $user = factory(User::class)->create();
        $garden = factory(Garden::class)->create(['user_id' => $user->id]);
        $history = factory(GardenHistory::class)->create(['garden_id' => $garden->id]);
        $plantHistory = factory(PlantHistory::class)->create(['history_id' => $history->id]);
        $locationHistory = factory(PlantLocationHistory::class)->create(['plant_history_id' => $plantHistory->id]);

        $response = $this->actingAs($user, 'web')->json('GET', '/api/getPlantHistory', ['historyId' => $history->id]);
        $newPlant = json_decode($response->getContent())[0];

        $response->assertStatus(200);
        $this->assertEquals($plantHistory->id, $newPlant->id);
        $this->assertEquals($plantHistory->plant_name, $newPlant->plant_name);
        $this->assertEquals($plantHistory->icon, $newPlant->icon);
    }

    public function testGetPlantLocationHistory()
    {
        $user = factory(User::class)->create();
        $garden = factory(Garden::class)->create(['user_id' => $user->id]);
        $history = factory(GardenHistory::class)->create(['garden_id' => $garden->id]);
        $plantHistory = factory(PlantHistory::class)->create(['history_id' => $history->id]);
        $locationHistory = factory(PlantLocationHistory::class)->create(['plant_history_id' => $plantHistory->id]);

        $response = $this->actingAs($user, 'web')->json('GET', '/api/getPlantLocationHistory', ['historyId' => $history->id]);
        $newLocation = json_decode($response->getContent())[0];

        $response->assertStatus(200);
        $this->assertEquals($locationHistory->id, $newLocation->id);
        $this->assertEquals($locationHistory->row, $newLocation->row);
        $this->assertEquals($locationHistory->column, $newLocation->column);
        $this->assertEquals($locationHistory->icon_location, $newLocation->icon_location);
        $this->assertEquals($locationHistory->plant_history_id, $newLocation->plant_history_id);
        $this->assertEquals($plantHistory->icon, $newLocation->icon);
        $this->assertEquals($plantHistory->plant_name, $newLocation->plant_name);
    }
}
