<?php

namespace App\Http\Controllers;

use App\Action\State\Create;
use App\Action\State\Delete;
use App\Action\State\Filter;
use App\Action\State\Read;
use App\Action\State\Update;
use App\Action\Manager;
use App\Model\State;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StateController extends Controller
{
    /**
     * @return Response
     */
    public function index(): Response
    {
        return new Response(State::all());
    }

    /**
     * @param Manager $manager
     * @param Request $request
     * @return Response
     */
    public function store(Manager $manager, Request $request): Response
    {
        $manager->addAction(Create::class, $request->toArray());

        return $this->handle($manager, ['success_status' => 204]);
    }

    /**
     * @param Manager $manager
     * @param string $id
     * @return Response
     */
    public function show(Manager $manager, string $id): Response
    {
        $manager->addAction(Read::class, ['id' => $id]);

        return $this->handle($manager, [
            'result' => Read::class,
            'response_handler' => [
                'id.Exists'=> 404
            ]
        ]);
    }

    /**
     * @param Manager $manager
     * @param Request $request
     * @param string $id
     * @return Response
     */
    public function update(Manager $manager, Request $request, string $id): Response
    {
        $data = collect(['id' => $id])->merge($request->all())->toArray();

        $manager->addAction(Update::class, $data);

        return $this->handle($manager, [
            'success_status' => 204,
            'response_handler' => [
                'id.Exists'=> 404
            ]
        ]);
    }

    /**
     * @param Manager $manager
     * @param string $id
     * @return Response
     */
    public function destroy(Manager $manager, string $id): Response
    {
        $manager->addAction(Delete::class, ['id' => $id]);

        return $this->handle($manager, [
            'success_status' => 204,
            'response_handler' => [
                'id.Exists'=> 404
            ]
        ]);
    }

    /**
     * @param Manager $manager
     * @param Request $request
     * @return Response
     */
    public function filter(Manager $manager, Request $request): Response
    {
        $manager->addAction(Filter::class, $request->all());

        return $this->handle($manager, [
            'result' => Filter::class
        ]);
    }
}