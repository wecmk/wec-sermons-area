<?php


namespace App\Services\Google;


use App\Repository\VariablesRepository;

class GoogleCredentials
{
    private VariablesRepository $variablesRepository;

    private string $accessTokenName = "GoogleCredentialsAccessToken";
    private string $refreshTokenName = "GoogleCredentialsRefreshToken";
    private string $expires = "GoogleCredentialsExpires";

    public function __construct(VariablesRepository $variablesRepository)
    {
        $this->variablesRepository = $variablesRepository;
    }

    public function getAccessToken(): ?string
    {
        return $this->variablesRepository->findByName($this->accessTokenName)->getValue();
    }

    public function setAccessToken(string $accessToken, $persistImmediately = true): void
    {
        $this->variablesRepository->setByName($this->accessTokenName, $accessToken, $persistImmediately);
    }

    public function getRefreshToken(): ?string
    {
        return $this->variablesRepository->findByName($this->refreshTokenName)->getValue();
    }

    public function setRefreshToken(string $refreshToken, $persistImmediately = true): void
    {
        $this->variablesRepository->setByName($this->refreshTokenName, $refreshToken, $persistImmediately);
    }

    public function getExpires(): ?int
    {
        return $this->variablesRepository->findByName($this->expires)->getValue(time()-10);
    }

    public function setExpires(int $expires, $persistImmediately = true): void
    {
        $this->variablesRepository->setByName($this->expires, $expires, $persistImmediately);
    }

    public function persist() {
        $this->variablesRepository->flush();
    }
}