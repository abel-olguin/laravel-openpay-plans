@extends('layouts.app')

@section('content')
    <div class="py-5 container mx-auto">
        <div class="flex flex-col gap-4">
            <div class="w-full flex justify-end">
                <a class="px-4 py-2 bg-sky-800 text-white rounded shadow-sm hover:bg-sky-950 hover:shadow-md"
                   href="{{route('plans.subscriptions.create')}}">{{__('Add new')}}</a>
            </div>
            @forelse($userPlans as $userPlan)
                <article
                    class="bg-gray-200 rounded-md shadow-md p-2 flex items-center justify-evenly {{$userPlan->active ? 'bg-green-50' : 'bg-red-50'}}">
                    <span>{{__('Plan')}}: <b>{{$userPlan->plan->name}}</b></span>
                    <span>
                    {{__('Status')}}:
                    <b class="{{$userPlan->active ? 'text-green-800' : 'text-red-800'}}">
                        {{$userPlan->active ? __('Active') : __('Inactive')}}
                    </b>
                </span>

                    <span>
                 {{__('Active until')}}: <time class="font-bold">{{$userPlan->subscription->period_end_date}}</time>
                </span>
                    @if($userPlan->active)
                        <button
                            onclick="confirm('{{__('Are you sure?')}}') && document.getElementById('cancelFrom').submit();"
                            class="rounded-full p-2 hover:bg-red-100 hover:shadow-md" title="{{__('Cancel')}}">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                 stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m6 4.125 2.25 2.25m0 0 2.25 2.25M12 13.875l2.25-2.25M12 13.875l-2.25 2.25M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/>
                            </svg>
                        </button>


                        <form id="cancelFrom" action="{{ route('plans.subscriptions.cancel', $userPlan) }}"
                              method="POST" style="display: none;">
                            @method('put')
                            @csrf
                        </form>
                    @endif
                </article>
            @empty
                <article
                    class="bg-gray-200 rounded-md shadow-md p-2 flex items-center justify-evenly ">
                    {{__('You don\'t have any active subscription.')}}
                </article>
            @endforelse
        </div>
    </div>
@endsection
