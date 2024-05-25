<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Todo;
use App\Models\TodoResponse;
use Ramsey\Uuid\Uuid;

class TodoController extends Controller
{
    public function index()
    {
        try {
            $todos = Todo::orderBy("updated_at", "desc")->get();
            $res = new TodoResponse(true, "Got todos", $todos);
            return response($res->to_json(), 200, ["Content-Type" => "application/json"]);
        } catch (\Exception $e) {
            $res = new TodoResponse(false, "Invalid request", $e->getMessage());
            return response($res->to_json(), 500, ["Content-Type" => "application/json"]);
        }
    }

    public function store()
    {
        try {
            $todo = new Todo();
            $todo->id = Uuid::uuid4();
            $todo->name = request("name");
            $todo->status = request("status");
            $data = $todo->saveOrFail();

            if (!$data) {
                $res = new TodoResponse(false, "Invalid request", null);
                return response($res->to_json(), 400, ["Content-Type" => "application/json"]);
            }
            $res = new TodoResponse(true, "Created todo", $todo);
            return response($res->to_json(), 201, ["Content-Type" => "application/json"]);
        } catch (\Exception $e) {
            $res = new TodoResponse(false, "Invalid request", $e->getMessage());
            return response($res->to_json(), 500, ["Content-Type" => "application/json"]);
        }
    }

    public function show($id)
    {
        try {
            $todo = Todo::query()->where("id", "=", $id)->get();

            if (!count($todo)) {
                $res = new TodoResponse(false, "Invalid request", null);
                return response($res->to_json(), 400, ["Content-Type" => "application/json"]);
            }
            $res = new TodoResponse(true, "Got todo", $todo);
            return response($res->to_json(), 200, ["Content-Type" => "application/json"]);
        } catch (\Exception $e) {
            $res = new TodoResponse(false, "Invalid request", $e->getMessage());
            return response($res->to_json(), 400, ["Content-Type" => "application/json"]);
        }
    }

    public function update($id)
    {
        try {
            $todo = Todo::query()->where("id", "=", $id)->get();

            if (!count($todo)) {
                $res = new TodoResponse(false, "Invalid request", null);
                return response($res->to_json(), 400, ["Content-Type" => "application/json"]);
            }

            $updated_todo = new Todo();
            $updated_todo->id = $id;
            $updated_todo->name = request("name");
            $updated_todo->status = request("status");

            $result = Todo::query()->where("id", "=", $id)->update([
                'id' => $id,
                'name' => $updated_todo->name,
                'status' => $updated_todo->status
            ]);

            if (!$result) {
                $res = new TodoResponse(false, "Invalid request", null);
                return response($res->to_json(), 400, ["Content-Type" => "application/json"]);
            }

            $res = new TodoResponse(true, "Updated todo", $updated_todo);
            return response($res->to_json(), 201, ["Content-Type" => "application/json"]);
        } catch (\Exception $e) {
            $res = new TodoResponse(false, "Invalid request", $e->getMessage());
            return response($res->to_json(), 400, ["Content-Type" => "application/json"]);
        }
    }

    public function delete($id)
    {
        try {
            $todo = Todo::query()->where('id', "=", $id);

            if (!count($todo->get())) {
                $res = new TodoResponse(false, "Invalid request", null);
                return response($res->to_json(), 400, ["Content-Type" => "application/json"]);
            }

            $result = $todo->delete();

            if (!$result) {
                $res = new TodoResponse(false, "Invalid request", null);
                return response($res->to_json(), 400, ["Content-Type" => "application/json"]);
            }

            $res = new TodoResponse(true, "Deleted todo", $todo);
            return response($res->to_json(), 200, ["Content-Type" => "application/json"]);
        } catch (\Exception $e) {
            $res = new TodoResponse(false, "Invalid request", $e->getMessage());
            return response($res->to_json(), 400, ["Content-Type" => "application/json"]);
        }
    }
}