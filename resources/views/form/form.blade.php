<input type="hidden" name="trial" value="0">
@error('trial')
<span class="col-span-2 text-red-900">{{$message}}</span>
@enderror
<div class="flex flex-col gap-2">
    <label for="card_number">{{__('Card number')}}</label>
    <input type="text" id="card_number" name="card[card_number]" class="bg-gray-200 shadow-md rounded px-4 py-2"
           value="{{old('card.card_number')}}">
    @error('card.card_number')
    <small class="text-sm text-red-900">{{$message}}</small>
    @enderror
</div>

<div class="flex flex-col gap-2">
    <label for="holder_name">{{__('Holder name')}}</label>
    <input type="text" id="holder_name" name="card[holder_name]" class="bg-gray-200 shadow-md rounded px-4 py-2"
           value="{{old('card.holder_name')}}">
    @error('card.holder_name')
    <small class="text-sm text-red-900">{{$message}}</small>
    @enderror
</div>

<div class="flex flex-col gap-2">
    <label for="cvv2">{{__('CVV')}}</label>
    <input type="text" id="cvv2" name="card[cvv2]" class="bg-gray-200 shadow-md rounded px-4 py-2"
           value="{{old('card.cvv2')}}">
    @error('card.cvv2')
    <small class="text-sm text-red-900">{{$message}}</small>
    @enderror
</div>


<div class="flex gap-x-2">
    <div class="flex-1 flex flex-col gap-2">
        <label for="expiration_month">{{__('Card month')}}</label>
        <input type="text" id="expiration_month" name="card[expiration_month]"
               class="bg-gray-200 shadow-md rounded px-4 py-2" placeholder="MM"
               value="{{old('card.expiration_month')}}">
        @error('card.expiration_month')
        <small class="text-sm text-red-900">{{$message}}</small>
        @enderror
    </div>

    <div class="flex-1 flex flex-col gap-2">
        <label for="expiration_year">{{__('Card year')}}</label>
        <input type="text" placeholder="YY" id="expiration_year" name="card[expiration_year]"
               class="bg-gray-200 shadow-md rounded px-4 py-2"
               value="{{old('card.expiration_year')}}">
        @error('card.expiration_year')
        <small class="text-sm text-red-900">{{$message}}</small>
        @enderror
    </div>
</div>

<div class="flex flex-col gap-2">
    <label for="plan">{{__('Plan')}}</label>
    <select name="plan_id" id="plan" class="bg-gray-200 shadow-md rounded px-4 py-2">
        <option disabled {{!old('plan_id') ? 'selected' : ''}}>{{__('Choose one option')}}</option>
        @foreach($plans as $plan)
            <option value="{{$plan->id}}" {{old('plan_id') === $plan->id ? 'selected' : ''}}>{{ $plan->name }}</option>
        @endforeach
    </select>
    @error('plan_id')
    <small class="text-sm text-red-900">{{$message}}</small>
    @enderror
</div>

<div class="col-span-2 flex justify-end">
    <button class="px-4 py-2 bg-sky-800 text-white rounded shadow-sm hover:bg-sky-950 hover:shadow-md"
            type="submit">{{__('Save')}}
    </button>
</div>
