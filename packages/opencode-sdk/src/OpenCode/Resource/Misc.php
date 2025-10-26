<?php

declare(strict_types=1);

namespace ArtisanBuild\OpencodeSdk\OpenCode\Resource;

use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\AppAgents;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\AppLog;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\AuthSet;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\CommandList;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\ConfigGet;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\ConfigProviders;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\ConfigUpdate;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\EventSubscribe;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\FileList;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\FileRead;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\FileStatus;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\FindFiles;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\FindSymbols;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\FindText;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\McpStatus;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\PathGet;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\PostSessionIdPermissionsPermissionId;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\ProjectCurrent;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\ProjectList;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\SessionAbort;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\SessionChildren;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\SessionCommand;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\SessionCreate;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\SessionDelete;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\SessionDiff;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\SessionFork;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\SessionGet;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\SessionInit;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\SessionList;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\SessionMessage;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\SessionMessages;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\SessionPrompt;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\SessionRevert;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\SessionShare;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\SessionShell;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\SessionSummarize;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\SessionTodo;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\SessionUnrevert;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\SessionUnshare;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\SessionUpdate;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\ToolIds;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\ToolList;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\TuiAppendPrompt;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\TuiClearPrompt;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\TuiExecuteCommand;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\TuiOpenHelp;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\TuiOpenModels;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\TuiOpenSessions;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\TuiOpenThemes;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\TuiShowToast;
use ArtisanBuild\OpencodeSdk\OpenCode\Requests\Misc\TuiSubmitPrompt;
use Saloon\Http\BaseResource;
use Saloon\Http\Response;

class Misc extends BaseResource
{
    public function projectList(?string $directory = null): Response
    {
        return $this->connector->send(new ProjectList($directory));
    }

    public function projectCurrent(?string $directory = null): Response
    {
        return $this->connector->send(new ProjectCurrent($directory));
    }

    public function configGet(?string $directory = null): Response
    {
        return $this->connector->send(new ConfigGet($directory));
    }

    public function configUpdate(?string $directory = null): Response
    {
        return $this->connector->send(new ConfigUpdate($directory));
    }

    public function toolIds(?string $directory = null): Response
    {
        return $this->connector->send(new ToolIds($directory));
    }

    public function toolList(?string $directory, string $provider, string $model): Response
    {
        return $this->connector->send(new ToolList($directory, $provider, $model));
    }

    public function pathGet(?string $directory = null): Response
    {
        return $this->connector->send(new PathGet($directory));
    }

    public function sessionList(?string $directory = null): Response
    {
        return $this->connector->send(new SessionList($directory));
    }

    public function sessionCreate(?string $directory = null): Response
    {
        return $this->connector->send(new SessionCreate($directory));
    }

    public function sessionGet(string $id, ?string $directory = null): Response
    {
        return $this->connector->send(new SessionGet($id, $directory));
    }

    public function sessionDelete(string $id, ?string $directory = null): Response
    {
        return $this->connector->send(new SessionDelete($id, $directory));
    }

    public function sessionUpdate(string $id, ?string $directory = null): Response
    {
        return $this->connector->send(new SessionUpdate($id, $directory));
    }

    public function sessionChildren(string $id, ?string $directory = null): Response
    {
        return $this->connector->send(new SessionChildren($id, $directory));
    }

    /**
     * @param  string  $id  Session ID
     */
    public function sessionTodo(string $id, ?string $directory = null): Response
    {
        return $this->connector->send(new SessionTodo($id, $directory));
    }

    /**
     * @param  string  $id  Session ID
     */
    public function sessionInit(string $id, ?string $directory = null): Response
    {
        return $this->connector->send(new SessionInit($id, $directory));
    }

    public function sessionFork(string $id, ?string $directory = null): Response
    {
        return $this->connector->send(new SessionFork($id, $directory));
    }

    public function sessionAbort(string $id, ?string $directory = null): Response
    {
        return $this->connector->send(new SessionAbort($id, $directory));
    }

    public function sessionShare(string $id, ?string $directory = null): Response
    {
        return $this->connector->send(new SessionShare($id, $directory));
    }

    public function sessionUnshare(string $id, ?string $directory = null): Response
    {
        return $this->connector->send(new SessionUnshare($id, $directory));
    }

    public function sessionDiff(string $id, ?string $directory = null, ?string $messageId = null): Response
    {
        return $this->connector->send(new SessionDiff($id, $directory, $messageId));
    }

    /**
     * @param  string  $id  Session ID
     */
    public function sessionSummarize(string $id, ?string $directory = null): Response
    {
        return $this->connector->send(new SessionSummarize($id, $directory));
    }

    /**
     * @param  string  $id  Session ID
     */
    public function sessionMessages(string $id, ?string $directory = null): Response
    {
        return $this->connector->send(new SessionMessages($id, $directory));
    }

    /**
     * @param  string  $id  Session ID
     */
    public function sessionPrompt(string $id, ?string $directory = null): Response
    {
        return $this->connector->send(new SessionPrompt($id, $directory));
    }

    /**
     * @param  string  $id  Session ID
     * @param  string  $messageId  Message ID
     */
    public function sessionMessage(string $id, string $messageId, ?string $directory = null): Response
    {
        return $this->connector->send(new SessionMessage($id, $messageId, $directory));
    }

    /**
     * @param  string  $id  Session ID
     */
    public function sessionCommand(string $id, ?string $directory = null): Response
    {
        return $this->connector->send(new SessionCommand($id, $directory));
    }

    /**
     * @param  string  $id  Session ID
     */
    public function sessionShell(string $id, ?string $directory = null): Response
    {
        return $this->connector->send(new SessionShell($id, $directory));
    }

    public function sessionRevert(string $id, ?string $directory = null): Response
    {
        return $this->connector->send(new SessionRevert($id, $directory));
    }

    public function sessionUnrevert(string $id, ?string $directory = null): Response
    {
        return $this->connector->send(new SessionUnrevert($id, $directory));
    }

    public function postSessionIdPermissionsPermissionId(
        string $id,
        string $permissionId,
        ?string $directory = null,
    ): Response {
        return $this->connector->send(new PostSessionIdPermissionsPermissionId($id, $permissionId, $directory));
    }

    public function commandList(?string $directory = null): Response
    {
        return $this->connector->send(new CommandList($directory));
    }

    public function configProviders(?string $directory = null): Response
    {
        return $this->connector->send(new ConfigProviders($directory));
    }

    public function findText(?string $directory, string $pattern): Response
    {
        return $this->connector->send(new FindText($directory, $pattern));
    }

    public function findFiles(?string $directory, string $query): Response
    {
        return $this->connector->send(new FindFiles($directory, $query));
    }

    public function findSymbols(?string $directory, string $query): Response
    {
        return $this->connector->send(new FindSymbols($directory, $query));
    }

    public function fileList(?string $directory, string $path): Response
    {
        return $this->connector->send(new FileList($directory, $path));
    }

    public function fileRead(?string $directory, string $path): Response
    {
        return $this->connector->send(new FileRead($directory, $path));
    }

    public function fileStatus(?string $directory = null): Response
    {
        return $this->connector->send(new FileStatus($directory));
    }

    public function appLog(?string $directory = null): Response
    {
        return $this->connector->send(new AppLog($directory));
    }

    public function appAgents(?string $directory = null): Response
    {
        return $this->connector->send(new AppAgents($directory));
    }

    public function mcpStatus(?string $directory = null): Response
    {
        return $this->connector->send(new McpStatus($directory));
    }

    public function tuiAppendPrompt(?string $directory = null): Response
    {
        return $this->connector->send(new TuiAppendPrompt($directory));
    }

    public function tuiOpenHelp(?string $directory = null): Response
    {
        return $this->connector->send(new TuiOpenHelp($directory));
    }

    public function tuiOpenSessions(?string $directory = null): Response
    {
        return $this->connector->send(new TuiOpenSessions($directory));
    }

    public function tuiOpenThemes(?string $directory = null): Response
    {
        return $this->connector->send(new TuiOpenThemes($directory));
    }

    public function tuiOpenModels(?string $directory = null): Response
    {
        return $this->connector->send(new TuiOpenModels($directory));
    }

    public function tuiSubmitPrompt(?string $directory = null): Response
    {
        return $this->connector->send(new TuiSubmitPrompt($directory));
    }

    public function tuiClearPrompt(?string $directory = null): Response
    {
        return $this->connector->send(new TuiClearPrompt($directory));
    }

    public function tuiExecuteCommand(?string $directory = null): Response
    {
        return $this->connector->send(new TuiExecuteCommand($directory));
    }

    public function tuiShowToast(?string $directory = null): Response
    {
        return $this->connector->send(new TuiShowToast($directory));
    }

    public function authSet(string $id, ?string $directory = null): Response
    {
        return $this->connector->send(new AuthSet($id, $directory));
    }

    public function eventSubscribe(?string $directory = null): Response
    {
        return $this->connector->send(new EventSubscribe($directory));
    }
}
