@extends('layouts.master')

@section('header')
    <h1 class="font-bold text-2xl py-4 text-white">
        Notifications
    </h1>
@endsection

@section('content')
    <div class="flex flex-col">
        <div class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8">
            <div class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>


                    <tr>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Message
                        </th>

                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Date
                        </th>

                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($notifications as $notification)
                    <tr class="{{ $notification->read() ? 'bg-gray-100' : '' }}">
                        <td class="px-6 py-4 border-b border-gray-200">

                            <div class="flex items-center">
                                <div class="flex-shrink-0 ">
                                    <span class="px-2 py-1 inline-flex  leading-5 font-semibold rounded-full {{$notification->data['status'] == 'error'? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-800'}} ">{{ $notification->data['status'] == 'error'? 'X' : '✓'}}  </span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm leading-5 font-medium text-gray-900">
                                        {{$notification->data['message']}}
                                    </div>

                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">
                            {{$notification->created_at}}
                        </td>
                    </tr>
                    @empty

                    <tr>
                        <td class="px-6 py-4 border-b border-gray-200">
                            <p class="text-sm italic leading-5 text-gray-900">
                                You don't have any notifications
                            </p>
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200 text-sm leading-5 text-gray-500">

                        </td>
                    </tr>
                    @endforelse

                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection

{{--@section('js')--}}
{{--    <script src="/js/app.js"></script>--}}
{{--@endsection--}}


