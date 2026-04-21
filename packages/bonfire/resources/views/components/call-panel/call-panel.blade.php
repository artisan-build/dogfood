@php
    $me = \ArtisanBuild\Bonfire\Facades\Bonfire::memberFor(auth()->user());
    $myName = $me?->display_name ?? auth()->user()?->name ?? 'You';
    $myAvatar = $me?->avatar_url ?? 'https://ui-avatars.com/api/?name='.urlencode($myName);
@endphp

<div x-data="bonfireCallPanel()"
     data-my-name="{{ $myName }}"
     data-my-avatar="{{ $myAvatar }}"
     x-on:bonfire-start-call.window="startCall($event.detail)"
     class="pointer-events-none fixed inset-0 z-[60]"
     :class="state === 'idle' ? 'pointer-events-none' : 'pointer-events-auto'">

    {{-- Incoming call toast --}}
    <div x-show="state === 'incoming'"
         x-transition.opacity.duration.150ms
         class="pointer-events-auto fixed right-4 top-4 w-80 overflow-hidden rounded-lg border border-zinc-200
                bg-white shadow-xl
                dark:border-zinc-700 dark:bg-zinc-900"
         style="display: none;">
        <div class="flex items-center gap-3 p-4">
            <img :src="incoming.avatar || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(incoming.name)"
                 alt="" class="size-12 animate-pulse rounded-md bg-zinc-200 object-cover dark:bg-zinc-700">
            <div class="min-w-0 flex-1">
                <div class="truncate text-sm font-semibold text-zinc-900 dark:text-zinc-100">
                    <span x-text="incoming.name"></span>
                </div>
                <div class="text-xs text-zinc-500">Incoming call…</div>
            </div>
        </div>
        <div class="flex border-t border-zinc-100 dark:border-zinc-800">
            <button type="button"
                    @click="decline()"
                    class="flex-1 border-r border-zinc-100 py-2 text-sm font-medium text-rose-600
                           hover:bg-rose-50
                           dark:border-zinc-800 dark:hover:bg-rose-950/40">
                Decline
            </button>
            <button type="button"
                    @click="accept()"
                    class="flex-1 py-2 text-sm font-medium text-emerald-600 hover:bg-emerald-50
                           dark:hover:bg-emerald-950/40">
                Accept
            </button>
        </div>
    </div>

    {{-- Active call overlay --}}
    <div x-show="state === 'calling' || state === 'connected'"
         x-transition.opacity.duration.150ms
         class="pointer-events-auto fixed inset-0 flex flex-col bg-zinc-900/95 backdrop-blur-sm"
         style="display: none;">

        <div class="flex flex-shrink-0 items-center justify-between border-b border-zinc-700/50 px-4 py-3">
            <div class="flex items-center gap-3 text-sm text-white">
                <span class="inline-flex items-center gap-1.5 rounded-full bg-zinc-800/80 py-1 pl-1 pr-2.5">
                    <img :src="me.avatar" alt="" class="size-6 rounded-full bg-zinc-700 object-cover">
                    <span class="font-medium" x-text="me.name"></span>
                </span>
                <flux:icon name="arrows-right-left" class="size-3.5 text-zinc-500" />
                <span class="inline-flex items-center gap-1.5 rounded-full bg-zinc-800/80 py-1 pl-1 pr-2.5">
                    <img :src="peer.avatar || ('https://ui-avatars.com/api/?name=' + encodeURIComponent(peer.name || 'User'))"
                         alt="" class="size-6 rounded-full bg-zinc-700 object-cover">
                    <span class="font-medium" x-text="peer.name || 'Call'"></span>
                </span>
            </div>

            <div class="flex items-center gap-2 text-sm">
                <span x-show="state === 'calling'"
                      class="inline-flex items-center gap-1.5 rounded-full bg-amber-500/20 px-3 py-1 text-xs font-medium text-amber-300"
                      style="display: none;">
                    <span class="relative flex size-2">
                        <span class="absolute inline-flex size-full animate-ping rounded-full bg-amber-300 opacity-75"></span>
                        <span class="relative inline-flex size-2 rounded-full bg-amber-300"></span>
                    </span>
                    Ringing…
                </span>
                <span x-show="state === 'connected'"
                      class="inline-flex items-center gap-2 rounded-full bg-emerald-500/15 px-3 py-1 font-mono text-sm text-emerald-300"
                      style="display: none;">
                    <span class="size-2 rounded-full bg-emerald-400"></span>
                    <span x-text="durationLabel"></span>
                </span>
            </div>

            <div class="w-20"></div>
        </div>

        <div class="relative flex-1 overflow-hidden">
            <video x-ref="remoteVideo"
                   autoplay
                   playsinline
                   class="h-full w-full bg-zinc-950 object-cover"></video>

            <div class="pointer-events-none absolute left-4 bottom-4 inline-flex items-center gap-1.5 rounded-md bg-black/50 px-2 py-1 text-xs text-white backdrop-blur-sm">
                <span x-text="peer.name || 'User'"></span>
                <span x-show="state === 'calling'" class="text-amber-300">· ringing</span>
            </div>

            <div x-show="state === 'calling'"
                 class="absolute inset-0 flex flex-col items-center justify-center gap-3 text-white"
                 style="display: none;">
                <img :src="peer.avatar || 'https://ui-avatars.com/api/?name=' + encodeURIComponent(peer.name || 'User')"
                     alt="" class="size-24 rounded-full bg-zinc-800 object-cover">
                <div class="text-lg font-semibold" x-text="peer.name"></div>
                <div class="flex items-center gap-2 text-sm text-zinc-300">
                    <span class="relative flex size-2">
                        <span class="absolute inline-flex size-full animate-ping rounded-full bg-amber-300 opacity-75"></span>
                        <span class="relative inline-flex size-2 rounded-full bg-amber-300"></span>
                    </span>
                    Ringing…
                </div>
            </div>

            <div class="absolute bottom-4 right-4 flex flex-col items-end gap-1">
                <video x-ref="localVideo"
                       autoplay
                       playsinline
                       muted
                       class="h-32 w-48 rounded-lg border border-zinc-700 bg-zinc-950 object-cover shadow-lg"></video>
                <span class="inline-flex items-center gap-1.5 rounded-md bg-black/50 px-2 py-0.5 text-[11px] text-white backdrop-blur-sm">
                    <span x-show="! sharing">You — </span>
                    <span x-show="! sharing" x-text="me.name"></span>
                    <span x-show="sharing" class="inline-flex items-center gap-1 text-emerald-300">
                        <flux:icon name="computer-desktop" class="size-3" />
                        Sharing screen
                    </span>
                    <span x-show="! micOn && ! sharing" class="text-rose-300">muted</span>
                </span>
            </div>
        </div>

        <div class="flex flex-shrink-0 items-center justify-center gap-3 border-t border-zinc-700/50 px-4 py-3">
            <button type="button"
                    @click="toggleMic()"
                    :title="micOn ? 'Mute' : 'Unmute'"
                    :class="micOn
                        ? 'bg-zinc-700 text-white hover:bg-zinc-600'
                        : 'bg-rose-600 text-white hover:bg-rose-700'"
                    class="inline-flex items-center justify-center rounded-full p-3">
                <flux:icon :name="'microphone'" class="size-5" x-show="micOn" />
                <svg x-show="! micOn" class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75a6 6 0 0 0 6-6v-1.5m-6 7.5a6 6 0 0 1-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M12 15.75a3 3 0 0 1-3-3V4.5a3 3 0 1 1 6 0v8.25a3 3 0 0 1-3 3Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="m3 3 18 18"/>
                </svg>
            </button>
            <button type="button"
                    @click="toggleCam()"
                    :title="camOn ? 'Turn camera off' : 'Turn camera on'"
                    :class="camOn
                        ? 'bg-zinc-700 text-white hover:bg-zinc-600'
                        : 'bg-rose-600 text-white hover:bg-rose-700'"
                    class="inline-flex items-center justify-center rounded-full p-3">
                <flux:icon name="video-camera" class="size-5" x-show="camOn" />
                <svg x-show="! camOn" class="size-5" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5 21 6.75v10.5L15.75 13.5m-12-3v6a2.25 2.25 0 0 0 2.25 2.25h7.5M3 3l18 18"/>
                </svg>
            </button>

            <div class="relative">
                <button type="button"
                        @click="sharing ? stopScreenShare() : (showShareOptions = ! showShareOptions)"
                        :title="sharing ? 'Stop sharing' : 'Share screen'"
                        :class="sharing
                            ? 'bg-emerald-600 text-white ring-2 ring-emerald-300 hover:bg-emerald-700'
                            : 'bg-sky-600 text-white ring-2 ring-sky-400/50 hover:bg-sky-500'"
                        class="inline-flex items-center justify-center rounded-full p-3">
                    <flux:icon name="computer-desktop" class="size-5" />
                </button>
                <div x-show="showShareOptions && ! sharing"
                     @click.outside="showShareOptions = false"
                     x-transition.opacity.duration.100ms
                     class="absolute bottom-full left-1/2 mb-2 w-72 -translate-x-1/2 rounded-lg border border-zinc-700 bg-zinc-900 p-3 text-xs text-zinc-100 shadow-xl"
                     style="display: none;">
                    <div class="mb-2 text-[11px] font-semibold uppercase tracking-wider text-zinc-400">
                        Share options
                    </div>
                    <div class="space-y-3">
                        <div>
                            <label class="mb-1 block text-[10px] font-medium text-zinc-400">Quality</label>
                            <select x-model="shareQuality"
                                    class="w-full rounded border border-zinc-700 bg-zinc-800 px-2 py-1 text-xs">
                                <option value="720">720p (HD)</option>
                                <option value="1080">1080p (Full HD)</option>
                                <option value="1440">1440p (QHD)</option>
                                <option value="2160">2160p (4K)</option>
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-[10px] font-medium text-zinc-400">Frame rate</label>
                            <select x-model="shareFps"
                                    class="w-full rounded border border-zinc-700 bg-zinc-800 px-2 py-1 text-xs">
                                <option value="15">15 fps (low bandwidth)</option>
                                <option value="30">30 fps (standard)</option>
                                <option value="60">60 fps (smooth, needs bandwidth)</option>
                            </select>
                        </div>
                        <p class="text-[10px] text-zinc-500 leading-relaxed">
                            Your browser will then let you pick which screen, window, or tab to share.
                        </p>
                        <button type="button"
                                @click="startScreenShare()"
                                class="w-full rounded-md bg-emerald-600 py-2 text-xs font-semibold text-white hover:bg-emerald-700">
                            Start sharing
                        </button>
                    </div>
                </div>
            </div>

            <div class="relative">
                <button type="button"
                        @click="showDevices = ! showDevices; if (showDevices) loadDevices()"
                        title="Devices"
                        class="inline-flex items-center justify-center rounded-full bg-zinc-700 p-3 text-white hover:bg-zinc-600">
                    <flux:icon name="cog-6-tooth" class="size-5" />
                </button>
                <div x-show="showDevices"
                     @click.outside="showDevices = false"
                     x-transition.opacity.duration.100ms
                     class="absolute bottom-full right-0 mb-2 w-72 rounded-lg border border-zinc-700 bg-zinc-900 p-3 text-xs text-zinc-100 shadow-xl"
                     style="display: none;">
                    <div class="space-y-3">
                        <div>
                            <label class="mb-1 block text-[10px] font-semibold uppercase tracking-wider text-zinc-400">
                                Microphone
                            </label>
                            <select x-model="selectedMic"
                                    @change="changeDevice('audio', $event.target.value)"
                                    class="w-full rounded border border-zinc-700 bg-zinc-800 px-2 py-1 text-xs">
                                <template x-for="d in devices.mics" :key="d.deviceId">
                                    <option :value="d.deviceId" x-text="d.label || 'Microphone'"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-[10px] font-semibold uppercase tracking-wider text-zinc-400">
                                Camera
                            </label>
                            <select x-model="selectedCam"
                                    @change="changeDevice('video', $event.target.value)"
                                    class="w-full rounded border border-zinc-700 bg-zinc-800 px-2 py-1 text-xs">
                                <template x-for="d in devices.cams" :key="d.deviceId">
                                    <option :value="d.deviceId" x-text="d.label || 'Camera'"></option>
                                </template>
                            </select>
                        </div>
                        <div>
                            <label class="mb-1 block text-[10px] font-semibold uppercase tracking-wider text-zinc-400">
                                Speaker / Headphones
                            </label>
                            <select x-model="selectedSpeaker"
                                    @change="changeDevice('output', $event.target.value)"
                                    class="w-full rounded border border-zinc-700 bg-zinc-800 px-2 py-1 text-xs">
                                <template x-for="d in devices.speakers" :key="d.deviceId">
                                    <option :value="d.deviceId" x-text="d.label || 'Speaker'"></option>
                                </template>
                                <template x-if="devices.speakers.length === 0">
                                    <option value="">Browser default (Firefox/Safari can't change output)</option>
                                </template>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <button type="button"
                    @click="hangUp()"
                    title="End call"
                    class="inline-flex items-center justify-center rounded-full bg-rose-600 p-3 text-white hover:bg-rose-700">
                <flux:icon name="phone-x-mark" class="size-5" />
            </button>
        </div>
    </div>
</div>

@once
    <script>
        window.bonfireCallPanel = function () {
            return {
                me: { name: 'You', avatar: null },
                state: 'idle', // idle | calling | incoming | connected
                sessionId: null,
                peer: { id: null, name: '', avatar: null, userId: null },
                incoming: { sessionId: null, name: '', avatar: null, callerUserId: null },
                pc: null,
                localStream: null,
                remoteStream: null,
                pendingIce: [],
                micOn: true,
                camOn: true,
                startedAt: null,
                durationTimer: null,
                durationLabel: '00:00',
                ringtoneCtx: null,
                ringtoneInterval: null,
                ringbackCtx: null,
                ringbackInterval: null,
                pendingOffer: null,
                sharing: false,
                screenStream: null,
                cameraTrack: null,
                showShareOptions: false,
                shareQuality: 1080,
                shareFps: 30,
                devices: { mics: [], cams: [], speakers: [] },
                selectedMic: '',
                selectedCam: '',
                selectedSpeaker: '',
                showDevices: false,
                iceServers: [
                    { urls: 'stun:stun.l.google.com:19302' },
                    { urls: 'stun:stun1.l.google.com:19302' },
                ],
                init() {
                    this.me.name = this.$el.dataset.myName || 'You';
                    this.me.avatar = this.$el.dataset.myAvatar || null;
                    this.subscribe();
                },
                subscribe() {
                    const tryAttach = () => {
                        if (typeof window.Echo === 'undefined') {
                            return setTimeout(tryAttach, 250);
                        }
                        const userId = {{ auth()->id() ?? 'null' }};
                        if (! userId) return;
                        const channel = window.Echo.private('App.Models.User.' + userId);
                        channel.listen('.call.initiated', (e) => this.onIncoming(e));
                        channel.listen('.call.signal', (e) => this.onSignal(e));
                        channel.listen('.call.ended', (e) => this.onRemoteEnded(e));
                    };
                    tryAttach();
                },
                async startCall(detail) {
                    if (this.state !== 'idle') return;
                    try {
                        this.peer = { id: detail.memberId, name: detail.name, avatar: detail.avatar, userId: null };
                        const result = await this.$wire.initiateCall(detail.roomId, detail.memberId);
                        if (! result) { this.reset(); return; }
                        this.sessionId = result.session_id;
                        this.peer.userId = result.target_user_id;
                        this.state = 'calling';
                        this.startRingback();
                        await this.setupLocalStream();
                        await this.createPeerConnection(true);
                    } catch (err) {
                        console.error('startCall failed', err);
                        this.reset();
                    }
                },
                onIncoming(e) {
                    if (this.state !== 'idle') return;
                    this.incoming = {
                        sessionId: e.sessionId,
                        name: e.callerName,
                        avatar: e.callerAvatar,
                        callerMemberId: e.callerMemberId,
                    };
                    this.state = 'incoming';
                    this.startRingtone();
                    this.ringTimeout = setTimeout(() => {
                        if (this.state === 'incoming') this.missedByTimeout();
                    }, 30000);
                },
                missedByTimeout() {
                    const sid = this.incoming.sessionId;
                    this.stopRingtone();
                    clearTimeout(this.ringTimeout);
                    this.state = 'idle';
                    this.incoming = { sessionId: null, name: '', avatar: null };
                    if (sid) this.$wire.endCall(sid, 'missed');
                },
                startRingtone() {
                    if (this.ringtoneCtx) return;
                    try {
                        const AudioCtx = window.AudioContext || window.webkitAudioContext;
                        if (! AudioCtx) return;
                        this.ringtoneCtx = new AudioCtx();
                        const play = () => {
                            const ctx = this.ringtoneCtx;
                            if (! ctx || ctx.state === 'closed') return;
                            const now = ctx.currentTime;
                            [0, 0.35].forEach((offset, i) => {
                                const osc = ctx.createOscillator();
                                const gain = ctx.createGain();
                                osc.type = 'sine';
                                osc.frequency.setValueAtTime(i === 0 ? 523.25 : 659.25, now + offset);
                                osc.connect(gain);
                                gain.connect(ctx.destination);
                                gain.gain.setValueAtTime(0, now + offset);
                                gain.gain.linearRampToValueAtTime(0.12, now + offset + 0.02);
                                gain.gain.linearRampToValueAtTime(0, now + offset + 0.28);
                                osc.start(now + offset);
                                osc.stop(now + offset + 0.3);
                            });
                        };
                        play();
                        this.ringtoneInterval = setInterval(play, 2000);
                    } catch (e) { /* best effort */ }
                },
                stopRingtone() {
                    if (this.ringtoneInterval) { clearInterval(this.ringtoneInterval); this.ringtoneInterval = null; }
                    if (this.ringtoneCtx) { try { this.ringtoneCtx.close(); } catch (e) {} this.ringtoneCtx = null; }
                },
                startRingback() {
                    if (this.ringbackCtx) return;
                    try {
                        const AudioCtx = window.AudioContext || window.webkitAudioContext;
                        if (! AudioCtx) return;
                        this.ringbackCtx = new AudioCtx();
                        const play = () => {
                            const ctx = this.ringbackCtx;
                            if (! ctx || ctx.state === 'closed') return;
                            const now = ctx.currentTime;
                            // Dual-tone 440+480Hz, 2s on / 4s off (NA ringback pattern, shortened)
                            const osc1 = ctx.createOscillator();
                            const osc2 = ctx.createOscillator();
                            const gain = ctx.createGain();
                            osc1.type = 'sine'; osc2.type = 'sine';
                            osc1.frequency.value = 440;
                            osc2.frequency.value = 480;
                            osc1.connect(gain); osc2.connect(gain);
                            gain.connect(ctx.destination);
                            gain.gain.setValueAtTime(0, now);
                            gain.gain.linearRampToValueAtTime(0.06, now + 0.04);
                            gain.gain.setValueAtTime(0.06, now + 1.5);
                            gain.gain.linearRampToValueAtTime(0, now + 1.6);
                            osc1.start(now); osc2.start(now);
                            osc1.stop(now + 1.7); osc2.stop(now + 1.7);
                        };
                        play();
                        this.ringbackInterval = setInterval(play, 3500);
                    } catch (e) { /* best effort */ }
                },
                stopRingback() {
                    if (this.ringbackInterval) { clearInterval(this.ringbackInterval); this.ringbackInterval = null; }
                    if (this.ringbackCtx) { try { this.ringbackCtx.close(); } catch (e) {} this.ringbackCtx = null; }
                },
                async accept() {
                    if (this.state !== 'incoming') return;
                    clearTimeout(this.ringTimeout);
                    this.stopRingtone();
                    this.sessionId = this.incoming.sessionId;
                    this.peer = {
                        id: this.incoming.callerMemberId,
                        name: this.incoming.name,
                        avatar: this.incoming.avatar,
                    };
                    this.state = 'connected';
                    this.startedAt = Date.now();
                    this.startDurationTimer();
                    try {
                        await this.setupLocalStream();
                        await this.createPeerConnection(false);
                        // Replay any offer (or ICE) that arrived before we clicked accept.
                        if (this.pendingOffer) {
                            const pending = this.pendingOffer;
                            this.pendingOffer = null;
                            await this.onSignal(pending);
                        }
                    } catch (err) {
                        console.error('accept failed', err);
                        this.hangUp();
                    }
                },
                decline() {
                    const sid = this.incoming.sessionId;
                    clearTimeout(this.ringTimeout);
                    this.stopRingtone();
                    this.state = 'idle';
                    this.incoming = { sessionId: null, name: '', avatar: null };
                    if (sid) this.$wire.endCall(sid, 'declined');
                },
                async setupLocalStream() {
                    if (this.localStream) return;
                    this.localStream = await navigator.mediaDevices.getUserMedia({
                        audio: true,
                        video: { width: { ideal: 1280 }, height: { ideal: 720 } },
                    });
                    this.$nextTick(() => {
                        if (this.$refs.localVideo) this.$refs.localVideo.srcObject = this.localStream;
                    });
                },
                async createPeerConnection(isInitiator) {
                    this.pc = new RTCPeerConnection({ iceServers: this.iceServers });

                    this.localStream.getTracks().forEach(track => this.pc.addTrack(track, this.localStream));

                    this.remoteStream = new MediaStream();
                    this.pc.addEventListener('track', (ev) => {
                        ev.streams[0].getTracks().forEach(t => this.remoteStream.addTrack(t));
                        this.$nextTick(() => {
                            if (this.$refs.remoteVideo) this.$refs.remoteVideo.srcObject = this.remoteStream;
                        });
                    });

                    this.pc.addEventListener('icecandidate', (ev) => {
                        if (ev.candidate) {
                            this.$wire.relaySignal(this.sessionId, 'ice', { candidate: ev.candidate.toJSON() });
                        }
                    });

                    this.pc.addEventListener('connectionstatechange', () => {
                        if (['failed', 'disconnected', 'closed'].includes(this.pc.connectionState)) {
                            if (this.state !== 'idle') this.hangUp();
                        }
                        if (this.pc.connectionState === 'connected' && this.state === 'calling') {
                            this.state = 'connected';
                            this.stopRingback();
                            this.startedAt = Date.now();
                            this.startDurationTimer();
                        }
                    });

                    if (isInitiator) {
                        const offer = await this.pc.createOffer();
                        await this.pc.setLocalDescription(offer);
                        this.$wire.relaySignal(this.sessionId, 'offer', { sdp: offer });
                    }
                },
                async onSignal(e) {
                    if (this.sessionId !== null && e.sessionId !== this.sessionId) return;
                    // Callee hasn't hit Accept yet — buffer anything that arrives early.
                    if (! this.pc) {
                        if (e.kind === 'offer') this.pendingOffer = e;
                        if (e.kind === 'ice') this.pendingIce.push(e.payload.candidate);
                        return;
                    }
                    try {
                        if (e.kind === 'offer') {
                            await this.pc.setRemoteDescription(new RTCSessionDescription(e.payload.sdp));
                            const answer = await this.pc.createAnswer();
                            await this.pc.setLocalDescription(answer);
                            this.$wire.relaySignal(this.sessionId, 'answer', { sdp: answer });
                            this.drainPendingIce();
                        } else if (e.kind === 'answer') {
                            await this.pc.setRemoteDescription(new RTCSessionDescription(e.payload.sdp));
                            this.drainPendingIce();
                        } else if (e.kind === 'ice') {
                            if (this.pc.remoteDescription) {
                                await this.pc.addIceCandidate(new RTCIceCandidate(e.payload.candidate));
                            } else {
                                this.pendingIce.push(e.payload.candidate);
                            }
                        }
                    } catch (err) {
                        console.error('onSignal error', e.kind, err);
                    }
                },
                async drainPendingIce() {
                    while (this.pendingIce.length) {
                        const c = this.pendingIce.shift();
                        try { await this.pc.addIceCandidate(new RTCIceCandidate(c)); } catch (e) {}
                    }
                },
                onRemoteEnded() {
                    this.reset();
                },
                toggleMic() {
                    if (! this.localStream) return;
                    this.micOn = ! this.micOn;
                    this.localStream.getAudioTracks().forEach(t => t.enabled = this.micOn);
                },
                toggleCam() {
                    if (! this.localStream) return;
                    this.camOn = ! this.camOn;
                    this.localStream.getVideoTracks().forEach(t => t.enabled = this.camOn);
                },
                hangUp() {
                    const sid = this.sessionId;
                    const wasCalling = this.state === 'calling';
                    this.reset();
                    if (sid) this.$wire.endCall(sid, wasCalling ? 'canceled' : 'ended');
                },
                async loadDevices() {
                    try {
                        const list = await navigator.mediaDevices.enumerateDevices();
                        this.devices.mics = list.filter(d => d.kind === 'audioinput');
                        this.devices.cams = list.filter(d => d.kind === 'videoinput');
                        this.devices.speakers = list.filter(d => d.kind === 'audiooutput');
                    } catch (e) { /* permission not granted yet */ }
                },
                async startScreenShare() {
                    if (! this.pc || this.sharing) return;
                    this.showShareOptions = false;
                    const heightMap = { 720: 720, 1080: 1080, 1440: 1440, 2160: 2160 };
                    const targetHeight = heightMap[this.shareQuality] ?? 1080;
                    const targetWidth = Math.round(targetHeight * 16 / 9);
                    try {
                        const stream = await navigator.mediaDevices.getDisplayMedia({
                            video: {
                                frameRate: { ideal: Number(this.shareFps), max: Number(this.shareFps) },
                                width: { ideal: targetWidth },
                                height: { ideal: targetHeight },
                            },
                            audio: false,
                        });
                        this.screenStream = stream;
                        const screenTrack = stream.getVideoTracks()[0];

                        const sender = this.pc.getSenders().find(s => s.track && s.track.kind === 'video');
                        if (sender) {
                            // Keep original camera track to restore later (do not stop it).
                            this.cameraTrack = sender.track;
                            await sender.replaceTrack(screenTrack);
                        }

                        // Reflect on local preview too.
                        if (this.$refs.localVideo) {
                            const preview = new MediaStream([screenTrack, ...(this.localStream?.getAudioTracks() || [])]);
                            this.$refs.localVideo.srcObject = preview;
                        }

                        this.sharing = true;
                        screenTrack.addEventListener('ended', () => this.stopScreenShare());
                    } catch (err) {
                        console.error('startScreenShare failed', err);
                    }
                },
                async stopScreenShare() {
                    if (! this.sharing) return;
                    try {
                        const sender = this.pc?.getSenders().find(s => s.track && s.track.kind === 'video');
                        if (sender && this.cameraTrack) {
                            await sender.replaceTrack(this.cameraTrack);
                        }
                        if (this.$refs.localVideo && this.localStream) {
                            this.$refs.localVideo.srcObject = this.localStream;
                        }
                    } catch (e) { console.error('restore camera failed', e); }
                    if (this.screenStream) {
                        this.screenStream.getTracks().forEach(t => t.stop());
                        this.screenStream = null;
                    }
                    this.cameraTrack = null;
                    this.sharing = false;
                },
                async changeDevice(kind, deviceId) {
                    if (kind === 'output') {
                        this.selectedSpeaker = deviceId;
                        const el = this.$refs.remoteVideo;
                        if (el && typeof el.setSinkId === 'function') {
                            try { await el.setSinkId(deviceId); }
                            catch (err) { console.error('setSinkId', err); }
                        }
                        return;
                    }
                    if (! this.localStream) return;
                    if (kind === 'audio') this.selectedMic = deviceId;
                    if (kind === 'video') this.selectedCam = deviceId;
                    try {
                        const fresh = await navigator.mediaDevices.getUserMedia(
                            kind === 'audio' ? { audio: { deviceId: { exact: deviceId } } }
                                             : { video: { deviceId: { exact: deviceId } } },
                        );
                        const newTrack = (kind === 'audio' ? fresh.getAudioTracks() : fresh.getVideoTracks())[0];
                        const sender = this.pc?.getSenders().find(s => s.track && s.track.kind === kind);
                        if (sender) await sender.replaceTrack(newTrack);
                        const oldTrack = (kind === 'audio' ? this.localStream.getAudioTracks() : this.localStream.getVideoTracks())[0];
                        if (oldTrack) { this.localStream.removeTrack(oldTrack); oldTrack.stop(); }
                        this.localStream.addTrack(newTrack);
                        if (this.$refs.localVideo && kind === 'video') this.$refs.localVideo.srcObject = this.localStream;
                    } catch (err) { console.error('changeDevice', err); }
                },
                reset() {
                    if (this.ringTimeout) { clearTimeout(this.ringTimeout); this.ringTimeout = null; }
                    this.stopRingtone();
                    this.stopRingback();
                    if (this.screenStream) { this.screenStream.getTracks().forEach(t => t.stop()); this.screenStream = null; }
                    if (this.cameraTrack) { this.cameraTrack = null; }
                    this.sharing = false;
                    this.showShareOptions = false;
                    if (this.durationTimer) { clearInterval(this.durationTimer); this.durationTimer = null; }
                    if (this.pc) { try { this.pc.close(); } catch (e) {} this.pc = null; }
                    if (this.localStream) { this.localStream.getTracks().forEach(t => t.stop()); this.localStream = null; }
                    if (this.remoteStream) { this.remoteStream.getTracks().forEach(t => t.stop()); this.remoteStream = null; }
                    this.pendingIce = [];
                    this.pendingOffer = null;
                    this.state = 'idle';
                    this.sessionId = null;
                    this.peer = { id: null, name: '', avatar: null };
                    this.incoming = { sessionId: null, name: '', avatar: null };
                    this.startedAt = null;
                    this.durationLabel = '00:00';
                    this.micOn = true;
                    this.camOn = true;
                    this.showDevices = false;
                },
                startDurationTimer() {
                    if (this.durationTimer) return;
                    this.durationTimer = setInterval(() => {
                        const s = Math.floor((Date.now() - this.startedAt) / 1000);
                        const m = Math.floor(s / 60);
                        this.durationLabel = String(m).padStart(2, '0') + ':' + String(s % 60).padStart(2, '0');
                    }, 1000);
                },
            };
        };
    </script>
@endonce
