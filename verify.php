<?php
require __DIR__ . '/vendor/autoload.php';

use MRivera\JWT\JWTVerifier;
use MRivera\JWT\JWTManager;

use Jose\Component\Checker\ClaimCheckerManager;
use Jose\Component\Checker;

// the jwt token
$token = file_get_contents('token.sample.jwt');

$jwtManager = new JWTManager();
$jwtVerifier = (new JWTVerifier())->setJWKSet(file_get_contents('oid_keys.sample.json'));

$jws = $jwtManager->getJWS($token);

$isVerified = $jwtVerifier->verify($jws);
if( !$isVerified ){
    die('The json web token is NOT verified' . PHP_EOL);
}

// Verify claims
$claimCheckerManager = ClaimCheckerManager::create(
    [
        new Checker\IssuedAtChecker(),
        new Checker\NotBeforeChecker(),
        new Checker\ExpirationTimeChecker(),
        new Checker\AudienceChecker('account'),
    ]
);

try{
    $claimCheckerManager->check($jwtManager->decodeJWS($jws));
}catch(\Exception $e){
    die($e->getMessage() . PHP_EOL);
}
