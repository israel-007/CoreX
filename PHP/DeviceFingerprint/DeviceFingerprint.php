<?php

class DeviceFingerprint
{
    private string $serverSecret;

    public function __construct(string $secretKey = 'adbgs-itrs-jter')
    {
        $this->serverSecret = $secretKey;
    }

    /**
     * Generate a unique device fingerprint
     */
    public function generate(): string
    {
        // Basic server variables
        $clientIp = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
        $accept = $_SERVER['HTTP_ACCEPT'] ?? 'Unknown';
        $acceptLanguage = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? 'Unknown';

        // OS & PHP environment details
        $osDetails = php_uname();
        $phpVersion = phpversion();
        $loadedExtensions = implode(',', get_loaded_extensions());
        $tempDir = sys_get_temp_dir();

        // Proxy detection
        $proxyHeaders = [
            'HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED',
            'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED'
        ];
        $proxyInfo = $this->getProxyInfo($proxyHeaders);

        // Additional server headers
        $httpHeaders = [
            'SERVER_SOFTWARE' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'SERVER_PROTOCOL' => $_SERVER['SERVER_PROTOCOL'] ?? 'Unknown',
            'REQUEST_METHOD' => $_SERVER['REQUEST_METHOD'] ?? 'Unknown'
        ];
        $headersString = implode('|', $httpHeaders);

        // Extra entropy (session & server secret)
        $sessionId = session_id() ?: 'no-session';

        // Combine all fingerprint data
        $fingerprintData = implode('|', [
            $clientIp, $userAgent, $accept, $acceptLanguage,
            $osDetails, $phpVersion, $loadedExtensions, $tempDir,
            $proxyInfo, $headersString,
            $sessionId, $this->serverSecret
        ]);

        // Secure hashing with HMAC
        return hash_hmac('sha256', $fingerprintData, $this->serverSecret);
    }

    /**
     * Fetch proxy-related information from headers
     */
    private function getProxyInfo(array $headers): string
    {
        $proxyData = [];
        foreach ($headers as $header) {
            if (!empty($_SERVER[$header])) {
                $proxyData[] = $_SERVER[$header];
            }
        }
        return implode(',', $proxyData) ?: 'No Proxy';
    }
}
