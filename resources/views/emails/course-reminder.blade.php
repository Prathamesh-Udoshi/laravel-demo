<x-mail::message>
{!! nl2br(e($emailBody)) !!}

<x-mail::button :url="config('app.url') . '/courses'">
Go to Course Workspace
</x-mail::button>

Sincerely,<br>
{{ config('app.name') }} Academic Office
</x-mail::message>
