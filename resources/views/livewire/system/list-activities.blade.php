<div class="max-w-7xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold">Audit Log</h1>
            <div class="text-sm opacity-60">Track system activities and events</div>
        </div>
        
        <!-- Filters -->
        <div class="flex flex-wrap items-center gap-3">
            <select wire:model.live="eventFilter" class="select select-bordered w-full md:w-auto">
                <option value="">All Events</option>
                <option value="created">Created</option>
                <option value="updated">Updated</option>
                <option value="deleted">Deleted</option>
                <option value="auth">Authentication</option>
                <option value="route_access">Page Visits</option>
            </select>
            
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Search logs..."
                class="input input-bordered w-full md:w-64" />
        </div>
    </div>

    <!-- Activity Table -->
    <div class="card bg-base-100 shadow-sm border border-base-200">
        <div class="card-body p-0">
            <div class="overflow-x-auto">
                <table class="table">
                    <thead class="bg-base-200">
                        <tr>
                            <th class="w-40">Time</th>
                            <th class="w-32">User</th>
                            <th class="w-24">Event</th>
                            <th>Description</th>
                            <th>Subject</th>
                            <th class="w-20">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($activities as $activity)
                            <tr class="hover:bg-base-200/30 transition-colors">
                                <td class="text-xs font-mono opacity-70">
                                    {{ $activity->created_at->format('M d, H:i:s') }}
                                </td>
                                <td>
                                    @if($activity->causer)
                                        <div class="flex items-center gap-2">
                                            <div class="avatar placeholder">
                                                <div class="bg-neutral-focus text-neutral-content rounded-full w-6">
                                                    <span class="text-[10px]">{{ substr($activity->causer->name, 0, 1) }}</span>
                                                </div>
                                            </div>
                                            <span class="text-sm font-medium">{{ $activity->causer->name }}</span>
                                        </div>
                                    @else
                                        <span class="text-xs opacity-50 italic">System/Guest</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        // Custom logs (auth, route_access) usually have 'event' as null but 'log_name' set
                                        $eventType = $activity->event ?? $activity->log_name;
                                        
                                        $badgeClass = match($eventType) {
                                            'created' => 'badge-success',
                                            'updated' => 'badge-info',
                                            'deleted' => 'badge-error',
                                            'auth' => 'badge-warning',
                                            'route_access' => 'badge-ghost',
                                            default => 'badge-neutral'
                                        };
                                    @endphp
                                    <span class="badge {{ $badgeClass }} badge-sm bg-opacity-10 dark:bg-opacity-20 border-none font-bold uppercase text-[10px]">
                                        {{ $eventType }}
                                    </span>
                                </td>
                                <td class="max-w-xs truncate" title="{{ $activity->description }}">
                                    {{ $activity->description }}
                                </td>
                                <td class="text-xs opacity-70">
                                    @if($activity->subject_type)
                                        <span class="font-mono">{{ class_basename($activity->subject_type) }}</span>
                                        <span class="opacity-50">#{{ $activity->subject_id }}</span>
                                    @else
                                        <span class="opacity-30">-</span>
                                    @endif
                                </td>
                                <td>
                                    <button wire:click="viewDetails({{ $activity->id }})" class="btn btn-ghost btn-xs">
                                        View
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-10 opacity-50">
                                    No activity logs found.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    {{ $activities->links() }}

    <!-- Details Modal -->
    @if($showDetailsModal && $selectedActivity)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 backdrop-blur-sm"
             wire:click.self="closeDetailsModal">
            <div class="card bg-base-100 w-full max-w-2xl shadow-2xl max-h-[85vh] overflow-hidden">
                <div class="card-header flex justify-between items-center p-4 border-b border-base-200">
                    <h3 class="font-bold text-lg">Activity Details</h3>
                    <button wire:click="closeDetailsModal" class="btn btn-ghost btn-sm btn-circle">
                        <span class="icon-[tabler--x] size-5"></span>
                    </button>
                </div>
                
                <div class="card-body overflow-y-auto p-0">
                    <div class="p-4 space-y-4">
                        <!-- Metadata Grid -->
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div class="p-3 bg-base-200 rounded-lg">
                                <div class="opacity-60 text-xs uppercase mb-1">Time</div>
                                <div class="font-mono">{{ $selectedActivity->created_at->format('Y-m-d H:i:s') }}</div>
                            </div>
                            <div class="p-3 bg-base-200 rounded-lg">
                                <div class="opacity-60 text-xs uppercase mb-1">Caused By</div>
                                <div class="font-medium">{{ $selectedActivity->causer?->name ?? 'System/Guest' }}</div>
                            </div>
                            <div class="p-3 bg-base-200 rounded-lg col-span-2">
                                <div class="opacity-60 text-xs uppercase mb-1">Description</div>
                                <div>{{ $selectedActivity->description }}</div>
                            </div>
                        </div>

                        <!-- Properties (Custom Data) -->
                        @if($selectedActivity->properties && $selectedActivity->properties->isNotEmpty())
                            <div class="divider text-xs opacity-50">Additional Properties</div>
                            <div class="bg-base-300 rounded-lg p-3 font-mono text-xs overflow-x-auto">
                                <pre class="text-base-content">{{ json_encode($selectedActivity->properties->except(['old', 'attributes']), JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                            </div>
                        @endif

                        <!-- Changes (Old vs New) -->
                        @if($selectedActivity->properties->has('attributes') || $selectedActivity->properties->has('old'))
                            <div class="divider text-xs opacity-50">Changes</div>
                            <div class="grid grid-cols-2 gap-4">
                                @if($selectedActivity->properties->has('old'))
                                    <div class="space-y-2">
                                        <div class="badge badge-neutral badge-sm">Old Values</div>
                                        <div class="bg-red-50 dark:bg-red-900/20 rounded-lg p-3 font-mono text-xs overflow-x-auto">
                                            <pre class="text-red-700 dark:text-red-200">{{ json_encode($selectedActivity->properties['old'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                        </div>
                                    </div>
                                @endif
                                
                                @if($selectedActivity->properties->has('attributes'))
                                    <div class="space-y-2">
                                        <div class="badge badge-success badge-sm">New Values</div>
                                        <div class="bg-green-50 dark:bg-green-900/20 rounded-lg p-3 font-mono text-xs overflow-x-auto">
                                            <pre class="text-green-700 dark:text-green-200">{{ json_encode($selectedActivity->properties['attributes'], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
