@extends("layouts.app") 
@section("pageTitle","Employee Benefits")
@section("content")
    <div class="container mx-auto">
        @include("employee.category-list")
        <h1 class="my-3 text-center"><strong><i>Employee Benefits Details</i></strong></h1>        
        <div class="mt-4">
            <p>
                Welcome to Groemp Employee Benefits Page. You will be able to select the benefits from the list selected by your company.
            </p>
            <p>
                Click on the tabs above to check the benefit list
            </p>
        </div>

        <div>
            <p> The benefits are divided into three categories</p>
            <ol>
                <li>
                    <b>Predefined Values</b>
                    <p>The benefits under this category have predefined Voucher Value. To avail the same, please follow the below steps:<p>
                    <ul>
                        <li>Select the denomination</li>
                        <li>Enter the number of vouchers you require</li>
                        <li>Click on Save Details to save the selection</li>
                    </ul>
                </li>
                <li>
                    <b>Free Number Field</b>
                    <p>The amount will be added to your card directly under this category. To avail the same, please follow the below steps:<p>
                    <ul>
                        <li>Enter the denomination for which you want to load the card.</li>
                        <li>The denomination must be in multiples of 100</li>
                        <li>Click on Save Amount to save the selection</li>
                    </ul>
                </li>
                <li>
                    <b>Free Text</b>
                    <p>Under this category, you need to provide the details. To avail the same, please follow the below steps:<p>
                    <ul>
                        <li>Enter the details.</li>
                        <li>Click on Save Details to save the selection</li>
                    </ul>
                </li>
            </ol>
        </div>
    </div>
@stop