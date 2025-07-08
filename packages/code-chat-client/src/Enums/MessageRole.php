<?php

namespace ArtisanBuild\CodeChatClient\Enums;

enum MessageRole: string
{
    case USER = 'user';
    case ASSISTANT = 'assistant';
    case SYSTEM = 'system';
    case ERROR = 'error';
}
