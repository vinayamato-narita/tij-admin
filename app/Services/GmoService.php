<?php
/*
    13.11.2019
    GmoService.php
*/

namespace App\Services;
use Log;

class GmoService{

    private $gmoURL;
    private $shopID;
    private $shopPass;

    public function __construct(){
        $this->gmoURL = config('gmo.url').'/payment/';
        $this->shopID = config('gmo.shopId');
        $this->shopPass = config('gmo.shopPassword');
    }

    public function alterTran($jobCd, $accessID, $accessPass, $amount) {
        $url = $this->gmoURL . "AlterTran.idPass";
        Log::info("alterTran");
        Log::info("jobCd:".$jobCd);
        Log::info("accessID:".$accessID);
        Log::info("accessPass:".$accessPass);
        Log::info("amount:".$amount);

        // リクエストコネクションの設定
        $curl=curl_init();
        curl_setopt( $curl, CURLOPT_POST, true );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $curl, CURLOPT_URL, $url );
        $param = [
            'ShopID'     => $this->shopID,
            'ShopPass'   => $this->shopPass,
            'AccessID'   => $accessID,
            'AccessPass' => $accessPass,
            'JobCd'      => $jobCd,
            'Amount'     => $amount
        ];
        // リクエストボディの生成
        curl_setopt( $curl, CURLOPT_POSTFIELDS, http_build_query( $param ) );

        // リクエスト送信
        $response = curl_exec( $curl );
        $curlinfo = curl_getinfo( $curl );
        curl_close( $curl );
        Log::info("response:".$response);

        // レスポンスチェック
        if( $curlinfo[ 'http_code' ] != 200 ){
            // エラー

            return false;
        }

        // レスポンスのエラーチェック
        $dataMap = explode('&', $response);
        $data = array();
        foreach ($dataMap as $value) {
            $splitArray = explode('=', $value, 2);
            if (2 == count($splitArray)) {
                $data[$splitArray[0]] = $splitArray[1];
            }
        }
        if( array_key_exists( 'ErrCode', $data ) ){
            // エラー

            return false;
        }

        // 正常

        return $data;
    }

    public function changeTran($jobCd, $accessID, $accessPass, $amount) {
        $url = $this->gmoURL . "ChangeTran.idPass";

        // リクエストコネクションの設定
        $curl=curl_init();
        curl_setopt( $curl, CURLOPT_POST, true );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $curl, CURLOPT_URL, $url );
        $param = [
            'ShopID'     => $this->shopID,
            'ShopPass'   => $this->shopPass,
            'AccessID'   => $accessID,
            'AccessPass' => $accessPass,
            'JobCd'      => $jobCd,
            'Amount'     => $amount
        ];
        // リクエストボディの生成
        curl_setopt( $curl, CURLOPT_POSTFIELDS, http_build_query( $param ) );

        // リクエスト送信
        $response = curl_exec( $curl );
        $curlinfo = curl_getinfo( $curl );
        curl_close( $curl );

        // レスポンスチェック
        if( $curlinfo[ 'http_code' ] != 200 ){
            // エラー

            return false;
        }

        // レスポンスのエラーチェック
        $dataMap = explode('&', $response);
        $data = array();
        foreach ($dataMap as $value) {
            $splitArray = explode('=', $value, 2);
            if (2 == count($splitArray)) {
                $data[$splitArray[0]] = $splitArray[1];
            }
        }
        if( array_key_exists( 'ErrCode', $data ) ){
            // エラー

            return false;
        }

        // 正常

        return $data;
    }

    public function searchTrade($orderID) {
        $url = $this->gmoURL . "SearchTrade.idPass";

        // リクエストコネクションの設定
        $curl=curl_init();
        curl_setopt( $curl, CURLOPT_POST, true );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $curl, CURLOPT_URL, $url );
        $param = [
            'ShopID'           => $this->shopID,
            'ShopPass'         => $this->shopPass,
            'OrderID'          => $orderID
        ];
        // リクエストボディの生成
        curl_setopt( $curl, CURLOPT_POSTFIELDS, http_build_query( $param ) );

        // リクエスト送信
        $response = curl_exec( $curl );
        $curlinfo = curl_getinfo( $curl );
        curl_close( $curl );

        // レスポンスチェック
        if( $curlinfo[ 'http_code' ] != 200 ){
            // エラー

            return false;
        }

        // レスポンスのエラーチェック
        $dataMap = explode('&', $response);
        $data = array();
        foreach ($dataMap as $value) {
            $splitArray = explode('=', $value, 2);
            if (2 == count($splitArray)) {
                $data[$splitArray[0]] = $splitArray[1];
            }
        }
        if( array_key_exists( 'ErrCode', $data ) ){
            // エラー

            return false;
        }

        // 正常

        return $data;
    }

    public function searchTradeMulti($orderID) {
        $url = $this->gmoURL . "SearchTradeMulti.idPass";

        // リクエストコネクションの設定
        $curl=curl_init();
        curl_setopt( $curl, CURLOPT_POST, true );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $curl, CURLOPT_URL, $url );
        $param = [
            'ShopID'           => $this->shopID,
            'ShopPass'         => $this->shopPass,
            'OrderID'          => $orderID,
            'PayType'          => 5
        ];
        // リクエストボディの生成
        curl_setopt( $curl, CURLOPT_POSTFIELDS, http_build_query( $param ) );

        // リクエスト送信
        $response = curl_exec( $curl );
        $curlinfo = curl_getinfo( $curl );
        curl_close( $curl );

        // レスポンスチェック
        if( $curlinfo[ 'http_code' ] != 200 ){
            // エラー

            return false;
        }

        // レスポンスのエラーチェック
        $dataMap = explode('&', $response);
        $data = array();
        foreach ($dataMap as $value) {
            $splitArray = explode('=', $value, 2);
            if (2 == count($splitArray)) {
                $data[$splitArray[0]] = $splitArray[1];
            }
        }
        if( array_key_exists( 'ErrCode', $data ) ){
            // エラー

            return false;
        }

        // 正常

        return $data;
    }

    public function paypalSales($orderID, $accessID, $accessPass, $amount) {
        $url = $this->gmoURL . "PaypalSales.idPass";
        Log::info("paypalSales");
        Log::info("orderID:".$orderID);
        Log::info("accessID:".$accessID);
        Log::info("accessPass:".$accessPass);
        Log::info("amount:".$amount);

        // リクエストコネクションの設定
        $curl=curl_init();
        curl_setopt( $curl, CURLOPT_POST, true );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $curl, CURLOPT_URL, $url );
        $param = [
            'ShopID'     => $this->shopID,
            'ShopPass'   => $this->shopPass,
            'AccessID'   => $accessID,
            'AccessPass' => $accessPass,
            'OrderID'    => $orderID,
            'Amount'     => $amount
        ];
        // リクエストボディの生成
        curl_setopt( $curl, CURLOPT_POSTFIELDS, http_build_query( $param ) );

        // リクエスト送信
        $response = curl_exec( $curl );
        $curlinfo = curl_getinfo( $curl );
        curl_close( $curl );
        Log::info("response:".$response);

        // レスポンスチェック
        if( $curlinfo[ 'http_code' ] != 200 ){
            // エラー

            return false;
        }

        // レスポンスのエラーチェック
        $dataMap = explode('&', $response);
        $data = array();
        foreach ($dataMap as $value) {
            $splitArray = explode('=', $value, 2);
            if (2 == count($splitArray)) {
                $data[$splitArray[0]] = $splitArray[1];
            }
        }
        if( array_key_exists( 'ErrCode', $data ) ){
            // エラー

            return false;
        }

        // 正常

        return $data;
    }

    public function cancelTranPaypal($orderID, $accessID, $accessPass, $amount) {
        $url = $this->gmoURL . "CancelTranPaypal.idPass";

        // リクエストコネクションの設定
        $curl=curl_init();
        curl_setopt( $curl, CURLOPT_POST, true );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $curl, CURLOPT_URL, $url );
        $param = [
            'ShopID'     => $this->shopID,
            'ShopPass'   => $this->shopPass,
            'AccessID'   => $accessID,
            'AccessPass' => $accessPass,
            'OrderID'    => $orderID,
            'Amount'     => $amount
        ];
        // リクエストボディの生成
        curl_setopt( $curl, CURLOPT_POSTFIELDS, http_build_query( $param ) );

        // リクエスト送信
        $response = curl_exec( $curl );
        $curlinfo = curl_getinfo( $curl );
        curl_close( $curl );

        // レスポンスチェック
        if( $curlinfo[ 'http_code' ] != 200 ){
            // エラー

            return false;
        }

        // レスポンスのエラーチェック
        $dataMap = explode('&', $response);
        $data = array();
        foreach ($dataMap as $value) {
            $splitArray = explode('=', $value, 2);
            if (2 == count($splitArray)) {
                $data[$splitArray[0]] = $splitArray[1];
            }
        }
        if( array_key_exists( 'ErrCode', $data ) ){
            // エラー

            return false;
        }

        // 正常

        return $data;
    }

}
