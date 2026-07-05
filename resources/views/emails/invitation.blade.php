<!DOCTYPE html>
<html>
<head>
    <title>Invitation</title>
</head>
<body>

<h2>Hello {{ $invitation->name }}</h2>

<p>You have been invited to join the URL Shortener System.</p>

<p>
    <a href="{{ route('invitations.show', $invitation->token) }}">
        Accept Invitation
    </a>
</p>

</body>
</html>