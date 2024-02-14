<div class="wrapper">
    <div class="sidebar">
        <header>
            @if (config('app.show_logo'))
                @php
                    $logo = base64Image('images/prescription-logo.png');
                @endphp
                <img class="logo" src="{{ $logo }}">
            @else
                <h1 style="font-size: 20px;" class="logo">{{ $pharmacy->CompanyName }}</h1>
            @endif
            <div class="company-info">
                <p style="text-transform: capitalize;">
                    {{ $pharmacy->CompanyName }} <br>
                    {{ $pharmacy->Address1 }}
                    @if ($pharmacy->Address2)
                        ,
                    @endif {{ $pharmacy->Address2 }} <br>
                    {{ $pharmacy->Address3 }}
                    @if ($pharmacy->Address3)
                        <br>
                    @endif
                    {{ $pharmacy->Address4 }}
                    @if ($pharmacy->Address4)
                        <br>
                    @endif
                    {{ $pharmacy->Postcode }} <br>
                    {{ $pharmacy->CName }}
                </p>
                <p>
                    @if (config('app.registration_style') == 'GB')
                        GPhC Registration Number: {{ $pharmacy->GPHCNO }} <br>
                    @endif
                    @if (config('app.registration_style') == 'GB')
                        Company Number:
                    @else
                        KYK Number:
                    @endif {{ $pharmacy->CompanyNumber }} <br>
                    Telephone: {{ $pharmacy->Telephone }}
                </p>
            </div>
        </header>
        <section>
            <div class="document-info">
                <h1 id="prescription_or_delivery">
                    @if ($params['otc'] > 0)
                        Prescription
                    @else
                        Delivery note
                    @endif
                </h1>
                <p>
                    <span>Order No: </span> {{ $prescription->PrescriptionID }} <br>
                    <span>Reference No: </span>{{ $prescription->ReferenceNumber }}<br>
                    <span>Date: </span> {{ gmdate('d/m/Y', $prescription->CreatedDate) }}
                </p>
            </div>
            <div class="patient-info">
                <h2>Patient information</h2>
                <h3>Personal Details</h3>
                <p>
                    Name: <span class="capitalize">{{ $prescription->Name }}</span> <span
                        class="capitalize">{{ $prescription->Surname }}</span><br>
                    Gender: {{ $genders[$prescription->Sex] }}<br>
                    DOB: {{ $prescription->DOB }}<br>
                    Age: {{ $prescription->Age }}<br>
                    <!-- Mobile: {{ $prescription->Mobile }}<br>
                    Telephone: {{ $prescription->Telephone }} -->
                </p>

                <h3>Home Address <span style="font-weight: 500">(DO NOT SHIP)</span></h3>
                <p>
                    {{ $prescription->Address1 }}
                    @if ($prescription->Address2)
                        ,
                    @endif {{ $prescription->Address2 }} <br>
                    {{ $prescription->Address3 }}
                    @if ($prescription->Address4)
                        ,
                    @endif {{ $prescription->Address4 }} <br>
                    {{ $prescription->Postcode }}<br>
                    {{ $prescription->CountryName }}
                </p>

                <h3>Shipping Address</h3>
                <p>
                    {{ $prescription->DAddress1 }}
                    @if ($prescription->DAddress2)
                        ,
                    @endif {{ $prescription->DAddress2 }} <br>
                    {{ $prescription->DAddress3 }}
                    @if ($prescription->DAddress4)
                        ,
                    @endif {{ $prescription->DAddress4 }} <br>
                    {{ $prescription->DPostcode }}<br>
                    {{ $prescription->CountryName }}
                </p>

            </div>
        </section>
    </div>

    <div class="content">
        <div class="products-info">
            <h2>Products</h2>
            @foreach ($products as $product)
                <div class="row">
                    <h3>{{ $product->Name }} {{ $product->Quantity * $product->Dosage }} {{ $product->Units }}</h3>
                    @if ($product->Instructions)
                        <h4>Directions:</h4>
                        <p>
                            {{ $product->Instructions }}
                        </p>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="prescriber-info">
            <h2>Prescriber</h2>
            <table style="width: 100%;">
                <tr>
                    <td>
                        <div class="doctor-signature">
                            @php
                                $url = base64Signature($prescription->DoctorID, $prescription->PrescriptionID);
                            @endphp
                            @if ($url)
                                <img src="{{ $url }}" style="max-width:120px; height:auto">
                                <br>
                            @endif
                            <span class="doctor-name">{{ $prescription->DTitle }} {{ $prescription->DName }}
                                {{ $prescription->DSurname }}</span>

                            <div style="font-size:11px;">
                                @if ($prescription->DoctorType == 1)
                                    General Medical Council
                                @elseif($prescription->DoctorType == 2)
                                    EU
                                @elseif($prescription->DoctorType == 3)
                                    General Pharmaceutical Council
                                @elseif($prescription->DoctorType == 4)
                                    TEST
                                @elseif($prescription->DoctorType == 5)
                                    Irish Medical Council
                                @else
                                @endif
                                Registration Number: {{ $prescription->GMCNO }}
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="doctor-address">
                            <p>
                                {{ $prescription->DoctorAddress1 }} <br>
                                {{ $prescription->DoctorAddress2 }} @if ($prescription->DoctorAddress3 || $prescription->DoctorAddress4)
                                    <br>
                                @endif
                                {{ $prescription->DoctorAddress3 }}{{ $prescription->DoctorAddress4 ? ', ' . $prescription->DoctorAddress4 : '' }}
                                <br>
                                {{ $prescription->DoctorPostCode }} <br>
                                {{ $prescription->DCName }}
                            </p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        @if ($prescription->DNotes)
                            <div class="doctor-url">
                                <span>Check my registration:</span>
                                <a href="{{ $prescription->DNotes }}">{{ $prescription->DNotes }}</a>
                            </div>
                        @endif
                    </td>
                </tr>
            </table>



        </div>
    </div>
</div>
