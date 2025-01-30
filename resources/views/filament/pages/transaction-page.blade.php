<x-filament-panels::page>
    <div class="space-y-6">
        <!-- Cards Section -->
        <div class="grid grid-cols-3 gap-6">
            <div class="col-span-2">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-semibold">My Cards</h2>
                    <a href="#" class="text-primary-600 hover:text-primary-700">+ Add Card</a>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    @foreach($cards as $card)
                        <div>
                            <div class="rounded-tl-[30px] m-0 rounded-tr-[30px] p-6 h-[180px] {{ $loop->iteration == 1 ? 'bg-gradient-to-br from-blue-600 to-blue-400 text-white' : ($loop->iteration == 2 ? 'bg-gradient-to-br from-blue-900 to-purple-900 text-white' : 'bg-white border text-black') }}">
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

             <!-- Expense Chart -->
             <div class="">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold">My Expense</h3>
                </div>

                <div class="h-[225px] bg-white shadow-sm rounded-xl ring-1 ring-gray-950/5 p-6" x-data="expenseChart(@js($monthlyExpenses))">
                    <canvas x-ref="canvas"></canvas>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div style="margin-top: 50px;">
            <div class="mb-6">
                <h3 class="mb-6 text-lg font-semibold">Recent Transactions</h3>

                <div class="flex space-x-4 border-b border-gray-200">
                    <button wire:click="$set('activeTab', 'all')"
                        class="px-4 py-2 -mb-px border-b-2 transition-all duration-500 {{ $activeTab === 'all' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        All Transactions
                    </button>

                    <button wire:click="$set('activeTab', 'income')"
                        class="px-4 py-2 -mb-px border-b-2 transition-all duration-500 {{ $activeTab === 'income' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Income
                    </button>

                    <button wire:click="$set('activeTab', 'expense')"
                        class="px-4 py-2 -mb-px border-b-2 transition-all duration-500 {{ $activeTab === 'expense' ? 'border-primary-600 text-primary-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                        Expense
                    </button>
                </div>
            </div>

            <div class="bg-white rounded-xl">
                {{ $table }}
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            document.addEventListener('alpine:init', () => {
                Alpine.data('expenseChart', (data) => ({
                    chart: null,
                    init() {
                        this.initChart(data);
                    },
                    initChart(data) {
                        const ctx = this.$refs.canvas.getContext('2d');

                        // Destroy existing chart if it exists
                        if (this.chart) {
                            this.chart.destroy();
                        }

                        this.chart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: data.map(d => d.month),
                                datasets: [{
                                    data: data.map(d => d.amount),
                                    backgroundColor: (context) => {
                                        const index = context.dataIndex;
                                        return index === 0 ? '#16DBCC' : '#E5E7EB';
                                    },
                                    borderRadius: 8,
                                    barThickness: 40,
                                }],
                            },
                            options: {
                                responsive: true,
                                maintainAspectRatio: false,
                                plugins: {
                                    legend: {
                                        display: false,
                                    },
                                },
                                scales: {
                                    y: {
                                        beginAtZero: true,
                                        grid: {
                                            display: false,
                                        },
                                        ticks: {
                                            callback: value => '$' + value.toLocaleString(),
                                        },
                                    },
                                    x: {
                                        grid: {
                                            display: false,
                                        },
                                    },
                                },
                            },
                        });
                    },
                }));
            });

            // Re-initialize chart when Livewire updates
            document.addEventListener('livewire:navigated', () => {
                Alpine.initTree(document.body);
            });
        </script>
    @endpush
</x-filament-panels::page>
