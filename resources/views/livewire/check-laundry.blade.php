<div class="mt-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-white">Riwayat Pesanan Saya</h2>
        <div class="flex gap-2">
            <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-full text-sm">
                {{ $this->getTable()->getRecords()->count() }} Pesanan
            </span>
        </div>
    </div>

    {{ $this->table }}
</div>
