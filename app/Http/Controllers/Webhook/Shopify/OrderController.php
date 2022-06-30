<?php

namespace App\Http\Controllers\Webhook\Shopify;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Exception;


class OrderController extends Controller
{
    const CLIENT_ID = 'l7f868c00d0127474d91b758288fd966e1';
    const CLIENT_SECRET = '1303920307f441a197c8058eb0d9967f';
    const FEDEX_BASE_URL = 'https://apis-sandbox.fedex.com';

    public function getAuthToken() {
        try {
            $response = Http::asForm()->post(OrderController::FEDEX_BASE_URL . '/oauth/token', [
                'grant_type' => 'client_credentials',
                'client_id' => OrderController::CLIENT_ID,
                'client_secret' => OrderController::CLIENT_SECRET
            ]);

            $body = $response->body();
            if (isset($body['access_token'])) {
                return [
                    'success' => true,
                    'body' => $body['access_token']
                ];
            } else {
                return [
                    'success' => false,
                    'body' => $body
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'body' => $e->getMessage()
            ];
        }
    }

    /**
     * Webhook function for create/update order in shopify
     *
     * @param   Request     $request
     * @return  json
     */
    public function create(Request $request) {
        $input = $request->all();

        $res = $this->getAuthToken();
        if (!$res['success']) {
            return response()->json([
                "success" => false,
                "message" => 'Auth Error'
            ], 200);
        }

        $accessToken = $res['body'];
    }
}
