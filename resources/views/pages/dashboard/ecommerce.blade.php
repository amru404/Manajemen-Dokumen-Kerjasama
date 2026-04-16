@extends('layouts.app')

@section('content')
  <div class="grid grid-cols-12 gap-4 md:gap-12">
    <div class="col-span-12 space-y-12 xl:col-span-12">
      <x-ecommerce.ecommerce-metrics :mou-count="$mouCount" :pks-count="$pksCount" :berita-acara-count="$beritaAcaraCount"/>
    </div>
    <div class="col-span-12 xl:col-span-12">
      <x-ecommerce.recent-orders :documents="$recentDocuments"/>
    </div>

  </div>
@endsection
