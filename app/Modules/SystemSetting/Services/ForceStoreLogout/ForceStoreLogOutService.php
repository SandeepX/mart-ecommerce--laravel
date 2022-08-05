<?php


namespace App\Modules\SystemSetting\Services\ForceStoreLogout;

use App\Modules\Store\Repositories\StoreRepository;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\TokenRepository;
use Laravel\Passport\RefreshTokenRepository;

class ForceStoreLogOutService
{
    private $storeDetailRepo;

    public function __construct(StoreRepository $storeDetailRepo)
    {
        $this->storeDetailRepo = $storeDetailRepo;
    }

    public function getAllStores()
    {
        try {
            return $this->storeDetailRepo->getAllActiveStore();

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    public function forceStoreLogout($validatedData)
    {
        DB::beginTransaction();
        try {
            $tokenRepository = app(TokenRepository::class);
            $refreshTokenRepository = app(RefreshTokenRepository::class);
            foreach ($validatedData['storeCode'] as $storeCode) {
                $store = $this->storeDetailRepo->findOrFailStoreByCode(
                    $storeCode,
                    ['user']
                );
                if ($accessToken = $store->user->tokens) {
                    foreach ($accessToken as $token) {
                        $tokenRepository->revokeAccessToken($token->id);

// Revoke all of the token's refresh tokens...
                        $refreshTokenRepository->revokeRefreshTokensByAccessTokenId($token->id);
//                        $refreshToken = DB::table('oauth_refresh_tokens')
//                            ->where('access_token_id', $token->id)
//                            ->update([
//                                'revoked' => true
//                            ]);
//                        $token->revoke();
                    }
                }
            }
            DB::commit();

        } catch (\Exception $exception) {
            DB::rollback();
            throw $exception;
        }

    }
}
