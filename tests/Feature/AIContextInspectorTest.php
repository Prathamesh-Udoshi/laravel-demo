<?php

test('context inspector page is accessible', function () {
    $response = $this->get('/ai-context-inspector');

    $response->assertStatus(200);
    $response->assertSee('Developer Diagnostic Playground');
});

test('context inspector send endpoint validates input', function () {
    $response = $this->postJson('/ai-context-inspector/send', []);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['prompt', 'provider']);
});
