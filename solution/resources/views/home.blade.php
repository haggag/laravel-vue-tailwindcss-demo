@extends('layouts.master')

@section('header')
    <div class="flex  items-baseline">
        <h1 class="font-bold text-2xl py-4 text-white">
            Your Balance
        </h1>
        <span class=" md:hidden ml-2"></span>
        <base-button type="button" @click.prevent="openEntryModal" :disable="locked">
            <svg  width="16" height="16" viewBox="0 0 14 14"> <g fill="#FFF"> <polygon points="8 0 8 14 6 14 6 0"/> <polygon points="14 6 14 8 0 8 0 6"/> </g> </svg>
            <span class="hidden font-bold md:block ml-4">ADD ENTRY</span>
        </base-button>
        <span class="md:hidden ml-2"></span>
        <base-button type="button" @click.prevent="openImportModal" :disable="locked">
            <svg  xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="10 10 14 17"><defs><filter id="a" width="121.8%" height="175%" x="-10.9%" y="-37.5%" filterUnits="objectBoundingBox"><feOffset dy="1" in="SourceAlpha" result="shadowOffsetOuter1"/><feGaussianBlur in="shadowOffsetOuter1" result="shadowBlurOuter1" stdDeviation="3"/><feColorMatrix in="shadowBlurOuter1" result="shadowMatrixOuter1" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.3 0"/><feMerge><feMergeNode in="shadowMatrixOuter1"/><feMergeNode in="SourceGraphic"/></feMerge></filter></defs><g fill="#FFF" filter="url(#a)" transform="translate(-1 1)"><path d="M15 22h6v-6h4l-7-7-7 7h4v6zm-4 2h14v2H11v-2z"/></g></svg>
            <span class="hidden font-bold md:block ml-4">IMPORT CSV</span>
        </base-button>
    </div>
    <div class="px-4  sm:px-6 text-right">
        <h3 class="text-sm fon-bold leading-6 font-bold text-gray-400">
            TOTAL BALANCE
        </h3>
        <user-balance />
    </div>
@endsection

@section('content')

    <entry-page  />
@endsection

@section('footer')
    <div>
        <base-modal title="Add Balance Entry" v-slot="scope" ref="modal1" >
            <entry-form  @close="scope.close"  submit_label="SAVE ENTRY" mode="create" />
        </base-modal>
        <base-modal title="Import Balance Entries" v-slot="scope" ref="modal2" >
            <import-form @close="scope.close" />
        </base-modal>
    </div>
@endsection
