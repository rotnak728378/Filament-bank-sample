<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Cards Section -->
        <div>
            <div class="grid grid-cols-3 gap-6">
                <div class="col-span-2">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-xl font-semibold">My Cards</h2>
                        <a href="/credit-card" class="text-primary-600 hover:text-primary-700">See All</a>
                    </div>
                    <div class="grid grid-cols-2 gap-6">
                        @foreach($cards as $card)
                            <div>
                                <div
                                    class="rounded-tl-[30px] m-0 rounded-tr-[30px] p-6 h-[180px] {{ $loop->iteration == 1 ? 'bg-gradient-to-br from-blue-600 to-blue-400 text-white' : ($loop->iteration == 2 ? 'bg-gradient-to-br from-blue-900 to-purple-900 text-white' : 'bg-white border text-black') }}">
                                    <div class="flex items-center justify-between mb-4">
                                        <div>
                                            <p class="text-sm opacity-80">Balance</p>
                                            <p class="text-2xl font-semibold">${{ number_format($card->balance, 2) }}</p>
                                        </div>
                                        @if ($loop->iteration != 3)
                                            <div class="w-10 h-10">
                                                <img width="35" src="{{ asset('/images/chip.png') }}" alt="">
                                            </div>
                                        @else
                                            <div class="w-10 h-10">
                                                <img width="35" src="{{ asset('/images/chip-gray.png') }}" alt="">
                                            </div>
                                        @endif

                                    </div>

                                    <div class="space-y-3">
                                        <div class="flex items-end justify-between">
                                            <div>
                                                <p class="text-sm opacity-80">CARD HOLDER</p>
                                                <p>{{ $card->holder_name }}</p>
                                            </div>

                                            <div>
                                                <p class="text-sm opacity-80">VALID THRU</p>
                                                <p>{{ \Carbon\Carbon::parse($card->expired_date)->format('m/y') }}</p>
                                            </div>
                                            <div></div>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    class="{{ $loop->iteration == 1 ? 'from-blue-600 to-blue-400' : ($loop->iteration == 2 ? 'from-blue-900 to-purple-900' : 'bg-white') }} bg-gradient-to-br opacity-90 rounded-bl-[30px] m-0 rounded-br-[30px] px-6 pt-3 m-0 flex items-center justify-between z-99 {{ $loop->iteration != 3 ? '' : 'border-1 border' }}">
                                    <div class="h-10">
                                        <p class="text-[22px] {{ $loop->iteration != 3 ? 'text-white' : 'text-black' }}">
                                            {{ substr_replace($card->card_number, ' **** **** ', 4, 8) }}
                                        </p>
                                    </div>
                                    <div class="w-10 h-10">
                                        <img width="35"
                                            src="{{ asset($loop->iteration != 3 ? '/images/merged-circle.png' : '/images/merged-circle-dark.png') }}"
                                            alt="merged-circle">
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Recent Transactions -->
                <div class="">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-semibold">Recent Transaction</h3>
                    </div>

                    <div class="space-y-4 bg-white rounded-2xl p-7 ring-1 ring-gray-950/5">
                        @foreach($recentTransactions as $transaction)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4">
                                    <div
                                        class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                                        <x-dynamic-component :component="$transaction['icon']"
                                            class="w-5 h-5 text-{{ $transaction['color'] }}-600" />
                                    </div>
                                    <div>
                                        <p class="font-medium">{{ $transaction['description'] }}</p>
                                        <p class="text-sm text-gray-500">{{ $transaction['date'] }}</p>
                                    </div>
                                </div>
                                <span class="text-{{ $transaction['amount'] < 0 ? 'danger' : 'success' }}-600 font-medium">
                                    {{ $transaction['amount'] < 0 ? '-' : '+' }}${{ number_format(abs($transaction['amount']), 0) }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>

        <!-- Main Content Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Column -->
            <div class="space-y-6">
                <!-- Weekly Activity -->
                <div class="">
                    <h3 class="text-lg font-semibold mb-6">Weekly Activity</h3>
                    <div class="h-[300px] bg-white rounded-xl p-6 ring-1 ring-gray-950/5"
                        x-data="weeklyChart(@js($weeklyActivity))"
                        wire:ignore>
                        <canvas x-ref="canvas"></canvas>
                    </div>

                </div>

                <!-- Quick Transfer -->
                <div class="p-0">
                    <h3 class="text-lg font-semibold mb-6">Quick Transfer</h3>
                    <div class="h-[300px] bg-white rounded-2xl p-6 ring-1 ring-gray-950/5 p-6">
                        <!-- Users Scroll Container -->
                        <div class="relative mb-8">
                            <div class="flex space-x-6 overflow-x-auto pb-4 scrollbar-hide">
                                @foreach($quickTransferUsers as $user)
                                    <button
                                        wire:click="$set('selectedUser', {{ $user['id'] }})"
                                        class="flex flex-col items-center space-y-3 min-w-[120px] focus:outline-none"
                                    >
                                        <div class="relative">
                                            <img
                                                src="{{ Storage::url($user['avatar']) }}"
                                                alt="{{ $user['name'] }}"
                                                class="w-20 h-20 rounded-full object-cover border-2 {{ $selectedUser === $user['id'] ? 'border-blue-600' : 'border-transparent' }}"
                                            >
                                            @if($selectedUser === $user['id'])
                                                <div class="absolute -right-1 -bottom-1 w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="text-center">
                                            <p class="font-semibold text-gray-900">{{ $user['name'] }}</p>
                                            <p class="text-sm text-blue-600">{{ $user['role'] }}</p>
                                        </div>
                                    </button>
                                @endforeach
                            </div>

                            <!-- Right Shadow/Scroll Indicator -->
                            <div class="absolute right-0 top-0 bottom-4 w-16 bg-gradient-to-l from-white pointer-events-none"></div>
                        </div>

                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-[16px]" style="color: #718EBF">Write Amount</p>
                            </div>
                            <div class="flex items-center bg-gray-100 rounded-full w-[300px]">
                                <input
                                  type="text"
                                  value="525.50"
                                  style="color: #718EBF"
                                  class="bg-transparent text-gray-600 text-[16px] px-6 py-3 w-full focus:outline-none focus:ring-0 border-0"
                                />
                                <button class="bg-blue-700 text-white px-8 py-3 rounded-full flex items-center space-x-2 hover:bg-blue-700 transition-colors">
                                  <span class="text-lg">Send</span>
                                  <svg
                                    class="w-5 h-5 transform rotate-45"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                  >
                                    <path
                                      strokeLinecap="round"
                                      strokeLinejoin="round"
                                      strokeWidth={2}
                                      d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"
                                    />
                                  </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-6">
                <!-- Expense Statistics -->
                <div class="p-0">
                    <h3 class="text-lg font-semibold mb-6">Expense Statistics</h3>
                    <div class="h-[300px] bg-white rounded-xl p-6 ring-1 ring-gray-950/5"
                        x-data="expenseChart(@js($expenseStats))"
                        wire:ignore>
                        <canvas x-ref="canvas"></canvas>
                    </div>
                </div>

                <!-- Balance History -->
                <div class="p-0">
                    <h3 class="text-lg font-semibold mb-6">Balance History</h3>
                    <div class="h-[300px] bg-white rounded-xl p-6 ring-1 ring-gray-950/5 p-6" x-data="balanceChart(@js($balanceHistory))" wire:ignore>
                        <canvas x-ref="canvas"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('weeklyChart', (initialData) => ({
                chart: null,
                init() {
                    this.$nextTick(() => {
                        this.createChart(initialData);
                    });

                    // Clean up on component destruction
                    this.$cleanup(() => {
                        if (this.chart) {
                            this.chart.destroy();
                            this.chart = null;
                        }
                    });
                },
                createChart(data) {
                    const ctx = this.$refs.canvas.getContext('2d');

                    if (this.chart) {
                        this.chart.destroy();
                    }

                    this.chart = new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                label: 'Deposit',
                                data: data.deposits,
                                backgroundColor: '#10B981',
                                borderRadius: 8,
                                barPercentage: 0.5,
                            }, {
                                label: 'Withdraw',
                                data: data.withdrawals,
                                backgroundColor: '#EF4444',
                                borderRadius: 8,
                                barPercentage: 0.5,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        display: false
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            },
                            plugins: {
                                legend: {
                                    position: 'top',
                                }
                            }
                        }
                    });
                }
            }));

            Alpine.data('expenseChart', (initialData) => ({
                chart: null,
                init() {
                    this.$nextTick(() => {
                        this.createChart(initialData);
                    });

                    this.$cleanup(() => {
                        if (this.chart) {
                            this.chart.destroy();
                            this.chart = null;
                        }
                    });
                },
                createChart(data) {
                    const ctx = this.$refs.canvas.getContext('2d');

                    if (this.chart) {
                        this.chart.destroy();
                    }

                    this.chart = new Chart(ctx, {
                        type: 'pie',
                        data: {
                            labels: data.map(d => d.label),
                            datasets: [{
                                data: data.map(d => d.percentage),
                                backgroundColor: data.map(d => d.color),
                                borderWidth: 0,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: 'right',
                                }
                            }
                        }
                    });
                }
            }));

            Alpine.data('balanceChart', (initialData) => ({
                chart: null,
                init() {
                    this.$nextTick(() => {
                        this.createChart(initialData);
                    });

                    this.$cleanup(() => {
                        if (this.chart) {
                            this.chart.destroy();
                            this.chart = null;
                        }
                    });
                },
                createChart(data) {
                    const ctx = this.$refs.canvas.getContext('2d');

                    if (this.chart) {
                        this.chart.destroy();
                    }

                    this.chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.labels,
                            datasets: [{
                                data: data.data,
                                borderColor: '#4F46E5',
                                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                                fill: true,
                                tension: 0.4,
                                borderWidth: 2,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    grid: {
                                        display: false
                                    },
                                    ticks: {
                                        callback: value => '$' + value.toLocaleString()
                                    }
                                },
                                x: {
                                    grid: {
                                        display: false
                                    }
                                }
                            },
                        }
                    });
                }
            }));
        });
    </script>
    @endpush
</x-filament-panels::page>
