<?php
require __DIR__ . '/vendor/autoload.php';

use Jose\Component\Core\JWKSet;
use Jose\Component\Core\Converter\StandardConverter;
use Jose\Component\Core\AlgorithmManager;

use Jose\Component\Signature\Algorithm\RS256;
use Jose\Component\Signature\JWSVerifier;
use Jose\Component\Signature\Serializer\JWSSerializerManager;
use Jose\Component\Signature\Serializer\CompactSerializer;

use Jose\Component\Checker\ClaimCheckerManager;
use Jose\Component\Checker;


// the algorithm to use
$algorithm = new RS256();
// The algorithm manager with the RS256 algorithm.
$algorithmManager = AlgorithmManager::create([
    $algorithm,
]);

// the jwks key set
$jwkSet = JWKSet::createFromJson(file_get_contents('oid_keys.sample.json'));
// the jwk to verify against
$jwk = $jwkSet->selectKey('sig', $algorithm);

// the jwt token
$token = file_get_contents('token.sample.jwt');

// The JSON Converter.
$jsonConverter = new StandardConverter();
// The serializer manager
$serializerManager = JWSSerializerManager::create([new CompactSerializer($jsonConverter)]);




// We try to load the token.
$jwt = $serializerManager->unserialize($token);
// We instantiate our JWS Verifier.
$jwsVerifier = new JWSVerifier(
    $algorithmManager
);
// We verify the signature. This method does NOT check the header.
// The arguments are:
// - The JWS object,
// - The key,
$isVerified = $jwsVerifier->verifyWithKey($jwt, $jwk, 0);

if( !$isVerified ){
    die('The json web token is NOT verified' . PHP_EOL);
}

// Verify claims
$claims = $jsonConverter->decode($jwt->getPayload());

$claimCheckerManager = ClaimCheckerManager::create(
    [
        new Checker\IssuedAtChecker(),
        new Checker\NotBeforeChecker(),
        new Checker\ExpirationTimeChecker(),
        new Checker\AudienceChecker('account'),
    ]
);

try{
    $claimCheckerManager->check($claims);
}catch(\Exception $e){
    die($e->getMessage() . PHP_EOL);
}
