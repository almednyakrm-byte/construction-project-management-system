<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\مشروعات;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Mockery;

class Testمشروعات extends TestCase
{
    protected $mockPDO;

    public function setUp(): void
    {
        $this->mockPDO = Mockery::mock('Illuminate\Database\Connection');
        $this->mockPDO->shouldReceive('select')->andReturnUsing(function ($query) {
            return [
                ['id' => 1, 'name' => 'مشروع 1'],
                ['id' => 2, 'name' => 'مشروع 2'],
            ];
        });
        $this->mockPDO->shouldReceive('insert')->andReturn(true);
        $this->mockPDO->shouldReceive('update')->andReturn(true);
        $this->mockPDO->shouldReceive('delete')->andReturn(true);
    }

    public function test_get_all()
    {
        $request = new Request();
        $response = app('App\Http\Controllers\مشروعاتController')->getAll($request);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_get_by_id()
    {
        $request = new Request();
        $response = app('App\Http\Controllers\مشروعاتController')->getById($request, 1);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_create()
    {
        $request = new Request(['name' => 'مشروع 3']);
        $response = app('App\Http\Controllers\مشروعاتController')->create($request);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function test_update()
    {
        $request = new Request(['name' => 'مشروع 1']);
        $response = app('App\Http\Controllers\مشروعاتController')->update($request, 1);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function test_delete()
    {
        $request = new Request();
        $response = app('App\Http\Controllers\مشروعاتController')->delete($request, 1);
        $this->assertEquals(200, $response->getStatusCode());
    }
}



// App\Models\مشروعات.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class مشروعات extends Model
{
    protected $fillable = ['name'];
}



// App\Http\Controllers\مشروعاتController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\مشروعات;

class مشروعاتController extends Controller
{
    public function getAll(Request $request)
    {
        $projects = مشروعات::all();
        return response()->json($projects, 200);
    }

    public function getById(Request $request, $id)
    {
        $project = مشروعات::find($id);
        return response()->json($project, 200);
    }

    public function create(Request $request)
    {
        $project = مشروعات::create($request->all());
        return response()->json($project, 201);
    }

    public function update(Request $request, $id)
    {
        $project = مشروعات::find($id);
        $project->update($request->all());
        return response()->json($project, 200);
    }

    public function delete(Request $request, $id)
    {
        مشروعات::destroy($id);
        return response()->json(null, 200);
    }
}