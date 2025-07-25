    <div class="max-w-10xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-900 sm:rounded-none p-0 shadow-none border border-gray-300 dark:border-gray-700">
            <div class="overflow-auto max-w-full max-h-[700px] text-sm font-sans border-t border-l border-gray-300 dark:border-gray-700">

                @php
                    use Carbon\Carbon;

                    $timeSlots = [
                        '08:00' => 'time_8_00_8_50',
                        '09:00' => 'time_9_00_9_50',
                        '10:00' => 'time_10_00_10_50',
                        '11:00' => 'time_11_00_11_50',
                        '12:00' => 'time_12_00_12_50',
                        '13:00' => 'time_13_00_13_50',
                        '14:00' => 'time_14_00_14_50',
                        '15:00' => 'time_15_00_15_50',
                        '16:00' => 'time_16_00_16_50',
                        '17:00' => 'time_17_00_17_50',
                    ];

                    $start = isset($startDate) ? Carbon::parse($startDate) : Carbon::now()->startOfWeek();
                    $end = isset($endDate) ? Carbon::parse($endDate) : $start->copy()->endOfWeek();

                     $dates = [];
                    for ($date = $start->copy(); $date->lte($end); $date->addDay()) {
                        // Exclude weekends (Saturday = 6, Sunday = 0)
                        if (!in_array($date->dayOfWeek, [0, 6])) {
                            $dates[] = $date->format('Y-m-d');
                        }
                    }
                    $groupedByDate = $groupedSchedules->groupBy(fn($g) => $g->first()->schedule_date);

                    $prevWeekStart = $start->copy()->subWeek()->toDateString();
                    $nextWeekStart = $start->copy()->addWeek()->toDateString();
                @endphp

                {{-- Week View Selector --}}
                <div class="sticky top-0 z-20 flex flex-col sm:flex-row items-center justify-between gap-4 p-4 bg-slate-100 dark:bg-gray-900 border-b border-gray-300 dark:border-gray-600">
                   <form method="GET" action="{{ route('schedules.index') }}">
                        <input type="hidden" name="start_date" value="{{ $prevWeekStart }}">
                        <input type="hidden" name="end_date" value="{{ Carbon::parse($prevWeekStart)->addDays(4)->toDateString() }}">
                        
                        @role('admin')
                        <button type="submit" class="bg-gray-700 hover:bg-transparent px-5 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider
                            border-2 border-gray-200 dark:border-gray-600 hover:border-gray-200 dark:hover:border-gray-400 text-gray-100 hover:text-gray-900 dark:text-white dark:hover:text-gray-200 rounded-lg transition ease-in duration-100">
                            ← Previous Week
                        </button>
                        @endrole
                    </form>

                    <div class="text-center font-bold text-orange-700 dark:text-orange-300 text-xl">
                        {{ $start->format('F j, Y') }} — {{ $end->format('F j, Y') }}
                    </div>

                    <form method="GET" action="{{ route('schedules.index') }}">
                        <input type="hidden" name="start_date" value="{{ $nextWeekStart }}">
                        <input type="hidden" name="end_date" value="{{ Carbon::parse($nextWeekStart)->next(Carbon::FRIDAY)->toDateString() }}">

                        @role('admin')
                        <button type="submit" class="bg-gray-700 hover:bg-transparent px-5 py-2 text-sm shadow-sm hover:shadow-lg font-medium tracking-wider
                            border-2 border-gray-200 dark:border-gray-600 hover:border-gray-200 dark:hover:border-gray-400 text-gray-100 hover:text-gray-900 dark:text-white dark:hover:text-gray-200 rounded-lg transition ease-in duration-100">
                            Next Week →
                        </button>
                        @endrole
                    </form>
                </div>

                <table class="min-w-full border-separate border-spacing-0 text-xs sm:text-xs md:text-base lg:text-xs text-gray-900 dark:text-white">
                    <thead class="sticky top-[71px] z-10 bg-gray-100 dark:bg-gray-700 border-b border-gray-300 dark:border-gray-600">
                        <tr>
                            <th class="border border-gray-300 dark:border-gray-700 bg-slate-200 dark:bg-gray-800 px-3 py-2 text-sm">Teacher</th>
                            <th class="border border-gray-300 dark:border-gray-700 bg-slate-200 dark:bg-gray-800 px-3 py-2 text-sm">Schedule Date</th>
                            @foreach(array_keys($timeSlots) as $time)
                                @php
                                    $startTime = Carbon::createFromFormat('H:i', $time);
                                    $endTime = $startTime->copy()->addMinutes(50);
                                @endphp
                                <th class="border border-gray-300 dark:border-gray-700 bg-slate-200 dark:bg-gray-800 px-3 py-2">
                                    {{ $startTime->format('H:i') }}<br>to<br>{{ $endTime->format('H:i') }}
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($dates as $date)
                            @php
                                $groups = $groupedByDate[$date] ?? collect();
                                $rowCount = $groups->count();
                                $formattedDate = Carbon::parse($date)->format('l');
                            @endphp

                            @if($groups->isEmpty())
                                <tr class="text-center text-sm">
                                    <td class="border px-4 py-2 dark:border-gray-600">No Schedule</td>
                                    <td class="border px-4 py-2 dark:border-gray-600" colspan="11"><span class="inline-flex items-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-yellow-600 dark:text-yellow-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M12 17h.01M21 12c0 4.97-4.03 9-9 9s-9-4.03-9-9 4.03-9 9-9 9 4.03 9 9z" />
                                        </svg>{{ $date }} ({{ $formattedDate }})
                                    </td>
                                </tr>
                            @else
                                @foreach($groups as $i => $group)
                                    <tr class="hover:bg-green-50 dark:hover:bg-gray-900 transition text-center text-xs align-middle">
                                        {{-- Teacher --}}
                                        <td class="px-4 py-2 border-t border-r border-gray-300 dark:border-gray-800 w-40 max-w-[160px] bg-slate-100 dark:bg-gray-900">
                                            {{ $group->first()->teacher->name ?? 'N/A' }}
                                        </td>

                                        {{-- Schedule Date --}}
                                        @if ($i === 0)
                                            <td class="px-4 py-2 border-t border-r border-l border-b border-gray-300 dark:border-gray-800 font-bold w-36 max-w-[140px] break-words align-middle bg-slate-100 dark:bg-gray-900 text-sm" rowspan="{{ $rowCount }}">
                                                {{ Carbon::parse($date)->format('F j, Y') }}
                                                <br>
                                                <span class="text-red-500">({{ $formattedDate }})</span>
                                            </td>
                                        @endif

                                        {{-- Time Slots --}}
                                        @foreach($timeSlots as $time => $slotKey)
                                            @php
                                                $scheduledStudents = $group->filter(fn($s) => $s->{$slotKey});
                                            @endphp
                                            <td class="px-2 py-2 border-t border-r bg-slate-100 dark:bg-gray-900 border-gray-300 dark:border-gray-800 w-56 max-w-[220px]">
                                                @if($scheduledStudents->isNotEmpty())
                                                    @foreach($scheduledStudents as $schedule)
                                                        @php
                                                            $status = $schedule->status ?? 'N/A';
                                                            $isAbsent = in_array($status, ['N/A', 'absent GRP', 'absent MTM']);
                                                            $textColor = $isAbsent ? 'text-red-700 dark:text-red-300' : 'text-green-700 dark:text-green-300';
                                                            $bgColor = $isAbsent ? 'bg-slate-100 dark:bg-gray-900' : 'bg-slate-100 dark:bg-gray-900';
                                                        @endphp
                                                        <div class="{{ $bgColor }} mb-1 p-1 text-[10px] sm:text-xs rounded leading-tight">
                                                            <div class="flex flex-col space-y-1 text-[11px] sm:text-xs font-bold">
                                                                <span class="whitespace-nowrap">
                                                                    {{ $schedule->student->name ?? 'N/A' }}
                                                                </span>
                                                                <span class="whitespace-nowrap">
                                                                    {{ $schedule->subject->subjectname ?? 'N/A' }}
                                                                </span>
                                                                <strong class="whitespace-nowrap">
                                                                    {{ $schedule->subTeacher->room->roomname ?? $schedule->teacher->room->roomname ?? 'No Room' }}
                                                                </strong>
                                                            </div>
                                                            <div>
                                                                @role('teacher')
                                                                    @php
                                                                        $subRoomId = $schedule->subTeacher->room->id ?? null;
                                                                        $mainRoomId = $schedule->teacher->room->id ?? null;
                                                                    @endphp
                                                                    @if ($subRoomId === $mainRoomId)
                                                                        <form action="{{ route('schedules.updateStatus', ['id' => $schedule->id]) }}" method="POST" class="flex flex-col gap-2 items-center">
                                                                            @csrf
                                                                            @method('PATCH')
                                                                            <select name="status" 
                                                                                class="status-select w-full max-w-[150px] rounded-lg border border-gray-300 dark:bg-gray-900 bg-gray-200 px-3 py-2 text-xs text-gray-900 dark:text-gray-100 
                                                                                dark:hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:border-gray-500 transition duration-200 ease-in-out"
                                                                                data-student-id="{{ $schedule->id }}">
                                                                                <option value="N/A" {{ $schedule->status === 'N/A' ? 'selected' : '' }}>N/A</option>
                                                                                <option value="present GRP" {{ $schedule->status === 'present GRP' ? 'selected' : '' }}>Present (GRP)</option>
                                                                                <option value="absent GRP" {{ $schedule->status === 'absent GRP' ? 'selected' : '' }}>Absent (GRP)</option>
                                                                                <option value="present MTM" {{ $schedule->status === 'present MTM' ? 'selected' : '' }}>Present (MTM)</option>
                                                                                <option value="absent MTM" {{ $schedule->status === 'absent MTM' ? 'selected' : '' }}>Absent (MTM)</option>
                                                                            </select>
                                                                            <button type="submit" 
                                                                                class="text-blue-500 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 text-sm cursor-pointer hover:underline">
                                                                                Update
                                                                            </button>
                                                                        </form>
                                                                    @else
                                                                        <span class="text-gray-400 italic text-xs">You are not authorized to update this.</span>
                                                                    @endif
                                                                @endrole
                                                                <span class="{{ $textColor }}">({{ $status }})</span>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @else
                                                    <span class="text-gray-400 dark:text-gray-500 italic align-middle">-----</span>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endif
                        @endforeach
                    </tbody>
                </table>
                <div id="teacherStudentsModalContainer"></div>
            </div>
        </div>
    </div>


@include('components.alerts.success')


    {{-- <script>
        function showTeacherStudents(teacherId, scheduleDate) {
            fetch(`/teachers/${teacherId}/students/${scheduleDate}`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('teacherStudentsModalContainer').innerHTML = html;
                    document.getElementById('teacherStudentsModal').classList.remove('hidden');
                    attachStatusChangeListeners();
                })
                .catch(error => {
                    console.error('Error loading students:', error);
                    alert('Error loading students. Please check the console.');
                });
        }

        function closeTeacherStudentsModal() {
            document.getElementById('teacherStudentsModal').classList.add('hidden');
        }

        function attachStatusChangeListeners() {
            document.querySelectorAll('.status-select').forEach(select => {
                select.addEventListener('change', function () {
                    let studentId = this.dataset.studentId;
                    let newStatus = this.value;
                    updateStudentStatus(studentId, newStatus);
                });
            });
        }

    </script>  --}}
