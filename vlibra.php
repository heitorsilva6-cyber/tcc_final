<?php

declare(strict_types=1);

function vlibraConfig(): array
{
    return [
        'base_url' => getenv('VLIBRA_BASE_URL') ?: 'https://api.vlibra.com.br',
        'api_key' => getenv('VLIBRA_API_KEY') ?: '',
        'secret_key' => getenv('VLIBRA_SECRET_KEY') ?: '',
        'timeout' => (int) (getenv('VLIBRA_TIMEOUT') ?: 20),
        'debug' => filter_var(getenv('VLIBRA_DEBUG') ?: '0', FILTER_VALIDATE_BOOLEAN),
    ];
}

function vlibraRequest(string $method, string $path, array $payload = []): array
{
    $config = vlibraConfig();

    if ($config['api_key'] === '' || $config['secret_key'] === '') {
        throw new RuntimeException('Credenciais do VLibra não configuradas. Defina VLIBRA_API_KEY e VLIBRA_SECRET_KEY.');
    }

    $ch = curl_init(rtrim($config['base_url'], '/') . $path);
    if ($ch === false) {
        throw new RuntimeException('Não foi possível iniciar a requisição cURL para o VLibra.');
    }

    $headers = [
        'Content-Type: application/json',
        'Accept: application/json',
        'Authorization: Bearer ' . $config['api_key'],
        'X-Api-Key: ' . $config['api_key'],
        'X-Secret-Key: ' . $config['secret_key'],
    ];

    curl_setopt_array($ch, [
        CURLOPT_CUSTOMREQUEST => strtoupper($method),
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_TIMEOUT => $config['timeout'],
        CURLOPT_CONNECTTIMEOUT => $config['timeout'],
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_POSTFIELDS => in_array(strtoupper($method), ['POST', 'PUT', 'PATCH'], true) ? json_encode($payload, JSON_UNESCAPED_UNICODE) : null,
    ]);

    $response = curl_exec($ch);
    $errno = curl_errno($ch);
    $error = curl_error($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($errno !== 0) {
        throw new RuntimeException('Erro de conexão com o VLibra: ' . $error);
    }

    $decoded = json_decode((string) $response, true);
    $result = is_array($decoded) ? $decoded : ['raw' => (string) $response];

    if ($httpCode >= 400) {
        throw new RuntimeException('VLibra respondeu com erro HTTP ' . $httpCode . ': ' . json_encode($result, JSON_UNESCAPED_UNICODE));
    }

    if ($config['debug']) {
        error_log('VLIBRA RESPONSE [' . $httpCode . ']: ' . (string) $response);
    }

    return $result;
}

function vlibraCreateCheckout(array $data): array
{
    $payload = [
        'amount' => (float) ($data['amount'] ?? 0),
        'currency' => $data['currency'] ?? 'BRL',
        'description' => $data['description'] ?? 'Pagamento',
        'reference' => $data['reference'] ?? uniqid('vlibra_', true),
        'customer' => $data['customer'] ?? [],
        'items' => $data['items'] ?? [],
        'return_url' => $data['return_url'] ?? '',
    ];

    return vlibraRequest('POST', '/v1/checkout', $payload);
}

function vlibraCheckStatus(string $paymentId): array
{
    return vlibraRequest('GET', '/v1/checkout/' . urlencode($paymentId));
}

function vlibraVerifyWebhook(array $payload): bool
{
    $secret = vlibraConfig()['secret_key'];
    $signature = $_SERVER['HTTP_X_VLIBRA_SIGNATURE'] ?? '';

    if ($signature === '') {
        return false;
    }

    $expected = hash_hmac('sha256', json_encode($payload, JSON_UNESCAPED_UNICODE), $secret);
    return hash_equals($expected, $signature);
}
