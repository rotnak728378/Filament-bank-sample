<!-- Card Detail Modal -->
<div x-data="{ open: false, card: null }" @open-card-detail.window="open = true; card = $event.detail">

    <!-- Modal Backdrop -->
    <div x-show="open" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[9999] bg-black/50" <!-- Updated z-index and opacity syntax -->
        @click="open = false">
    </div>

    <!-- Modal Content -->
    <div x-show="open" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
        x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
        class="fixed inset-0 z-[10000] flex items-center justify-center p-4">

        <div class="w-full max-w-2xl bg-white rounded-2xl shadow-xl" @click.away="open = false">
            <!-- Modal Header -->
            <div class="flex items-center justify-between p-6 border-b">
                <h3 class="text-xl font-semibold text-gray-900">Card Details</h3>
                <button @click="open = false" class="text-gray-400 hover:text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <!-- Card Preview -->
                <div class="mb-6">
                    <div class="rounded-tl-[30px] rounded-tr-[30px] p-6 h-[180px] bg-gradient-to-br from-blue-900 to-purple-900 text-white" style="margin-left: 100px; margin-right: 100px;">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <p class="text-sm opacity-80">Balance</p>
                                <p class="text-2xl font-semibold"
                                    x-text="card?.balance ? `$${Number(card.balance).toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}` : '0.00'">
                                </p>
                            </div>
                            <div class="w-10 h-10">
                                <img width="35" src="{{asset('/images/chip.png')}}" alt="chip">
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="flex items-end justify-between">
                                <div>
                                    <p class="text-sm opacity-80">CARD HOLDER</p>
                                    <p x-text="card?.holder_name"></p>
                                </div>
                                <div>
                                    <p class="text-sm opacity-80">VALID THRU</p>
                                    <p x-text="card?.expired_date"></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div
                        class="rounded-bl-[30px] rounded-br-[30px] px-6 pt-3 bg-gradient-to-br from-blue-900 to-purple-900 text-white" style="margin-left: 100px; margin-right: 100px;">
                        <div class="flex items-center justify-between">
                            <p class="text-[22px]" x-text="card?.card_number"></p>
                            <div class="w-10 h-10">
                                <img width="35" src="{{asset('/images/merged-circle.png')}}" alt="merged-circle">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Details -->
                <div class="grid grid-cols-2 gap-6" style="margin-left: 100px; margin-right: 100px;">
                    <div>
                        <p class="text-sm text-gray-600">Card Type</p>
                        <p class="font-medium" x-text="card?.type"></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Bank</p>
                        <p class="font-medium" x-text="card?.bank"></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        <div class="flex items-center mt-1">
                            <div class="w-2 h-2 mr-2 rounded-full" :class="card?->status === 'Active' ? 'bg-green-500' : 'bg-red-500'"></div>
                            <p class="font-medium" x-text="card?.status"></p>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Created Date</p>
                        <p class="font-medium" x-text="card?.created_at"></p>
                    </div>
                </div>
            </div>

            <!-- Modal Footer -->
            <div class="flex justify-end p-6 border-t space-x-3">
                <button @click="open = false"
                    class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Update the View Details link in the card list -->
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('cardDetails', () => ({
            openCardDetail(card) {
                this.$dispatch('open-card-detail', card);
            }
        }));
    });
</script>
