<div class="grid grid-cols-1 gap-4 sm:grid-cols-3 md:grid-cols-3 lg:gap-6">
    <div
      class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6"
    >
      <div
        class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-xl dark:bg-blue-800"
      >

       <i class="fa-regular fa-file text-blue-600"></i>
      </div>

      <div class="flex items-end justify-between mt-5">
        <div>
          <span class="text-sm text-gray-500 dark:text-gray-400">Total MOU</span>
          <h4 id="dashboard-mou-count" class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">{{ $mouCount ?? 0 }}</h4>
        </div>
      </div>
    </div>

    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
      <div
        class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-xl dark:bg-green-800">
        <i class="fa-regular fa-file text-green-600"></i>
      </div>

      <div class="flex items-end justify-between mt-5">
        <div>
          <span class="text-sm text-gray-500 dark:text-gray-400">Total PKS</span>
          <h4 id="dashboard-pks-count" class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">{{ $pksCount ?? 0 }}</h4>
        </div>
      </div>
    </div>


    <div class="rounded-2xl border border-gray-200 bg-white p-5 dark:border-gray-800 dark:bg-white/[0.03] md:p-6">
      <div class="flex items-center justify-center w-12 h-12 bg-purple-100 rounded-xl dark:bg-purple-800">
        <i class="fa-regular fa-file text-purple-600"></i>
      </div>

      <div class="flex items-end justify-between mt-5">
        <div>
          <span class="text-sm text-gray-500 dark:text-gray-400">Total Berita Acara</span>
          <h4 id="dashboard-berita-count" class="mt-2 font-bold text-gray-800 text-title-sm dark:text-white/90">{{ $beritaAcaraCount ?? 0 }}</h4>
        </div>
      </div>
    </div>
  </div>