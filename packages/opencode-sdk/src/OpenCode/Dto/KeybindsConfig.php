<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Dto;

use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data as SpatieData;

/**
 * Custom keybind configurations
 */
class KeybindsConfig extends SpatieData
{
    public function __construct(
        public ?string $leader = null,
        #[MapName('app_help')]
        public ?string $appHelp = null,
        #[MapName('app_exit')]
        public ?string $appExit = null,
        #[MapName('editor_open')]
        public ?string $editorOpen = null,
        #[MapName('theme_list')]
        public ?string $themeList = null,
        #[MapName('project_init')]
        public ?string $projectInit = null,
        #[MapName('tool_details')]
        public ?string $toolDetails = null,
        #[MapName('thinking_blocks')]
        public ?string $thinkingBlocks = null,
        #[MapName('session_export')]
        public ?string $sessionExport = null,
        #[MapName('session_new')]
        public ?string $sessionNew = null,
        #[MapName('session_list')]
        public ?string $sessionList = null,
        #[MapName('session_timeline')]
        public ?string $sessionTimeline = null,
        #[MapName('session_share')]
        public ?string $sessionShare = null,
        #[MapName('session_unshare')]
        public ?string $sessionUnshare = null,
        #[MapName('session_interrupt')]
        public ?string $sessionInterrupt = null,
        #[MapName('session_compact')]
        public ?string $sessionCompact = null,
        #[MapName('session_child_cycle')]
        public ?string $sessionChildCycle = null,
        #[MapName('session_child_cycle_reverse')]
        public ?string $sessionChildCycleReverse = null,
        #[MapName('messages_page_up')]
        public ?string $messagesPageUp = null,
        #[MapName('messages_page_down')]
        public ?string $messagesPageDown = null,
        #[MapName('messages_half_page_up')]
        public ?string $messagesHalfPageUp = null,
        #[MapName('messages_half_page_down')]
        public ?string $messagesHalfPageDown = null,
        #[MapName('messages_first')]
        public ?string $messagesFirst = null,
        #[MapName('messages_last')]
        public ?string $messagesLast = null,
        #[MapName('messages_copy')]
        public ?string $messagesCopy = null,
        #[MapName('messages_undo')]
        public ?string $messagesUndo = null,
        #[MapName('messages_redo')]
        public ?string $messagesRedo = null,
        #[MapName('model_list')]
        public ?string $modelList = null,
        #[MapName('model_cycle_recent')]
        public ?string $modelCycleRecent = null,
        #[MapName('model_cycle_recent_reverse')]
        public ?string $modelCycleRecentReverse = null,
        #[MapName('agent_list')]
        public ?string $agentList = null,
        #[MapName('agent_cycle')]
        public ?string $agentCycle = null,
        #[MapName('agent_cycle_reverse')]
        public ?string $agentCycleReverse = null,
        #[MapName('input_clear')]
        public ?string $inputClear = null,
        #[MapName('input_paste')]
        public ?string $inputPaste = null,
        #[MapName('input_submit')]
        public ?string $inputSubmit = null,
        #[MapName('input_newline')]
        public ?string $inputNewline = null,
        #[MapName('switch_mode')]
        public ?string $switchMode = null,
        #[MapName('switch_mode_reverse')]
        public ?string $switchModeReverse = null,
        #[MapName('switch_agent')]
        public ?string $switchAgent = null,
        #[MapName('switch_agent_reverse')]
        public ?string $switchAgentReverse = null,
        #[MapName('file_list')]
        public ?string $fileList = null,
        #[MapName('file_close')]
        public ?string $fileClose = null,
        #[MapName('file_search')]
        public ?string $fileSearch = null,
        #[MapName('file_diff_toggle')]
        public ?string $fileDiffToggle = null,
        #[MapName('messages_previous')]
        public ?string $messagesPrevious = null,
        #[MapName('messages_next')]
        public ?string $messagesNext = null,
        #[MapName('messages_layout_toggle')]
        public ?string $messagesLayoutToggle = null,
        #[MapName('messages_revert')]
        public ?string $messagesRevert = null,
    ) {}
}
