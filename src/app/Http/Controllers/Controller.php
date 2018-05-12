<?php

namespace App\Http\Controllers;

use App\Action\Manager;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param Manager $manager
     * @param array $options
     * @return Response
     */
    protected function handle(Manager $manager, array $options): Response
    {
        $collectOptions = collect($options);

        $manager->runActions();

        $messages = $manager->messages();

        if ($messages->isEmpty()) {

            $resultForAction = $collectOptions->get('result', null);

            $content = $manager->result($resultForAction);

            return new Response($content, $collectOptions->get('success_status', 200));
        }

        $responseHandler = $collectOptions->get('response_handler', []);

        foreach ($responseHandler as $rule => $httpCode) {
            if ($manager->thisRuleFailed($rule)) {

                return new Response(null, $httpCode);
            }
        }

        return new Response($messages->values()->first(), 422);
    }
}
