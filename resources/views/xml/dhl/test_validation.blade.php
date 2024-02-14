<req:ShipmentRequest xsi:schemaLocation="http://www.dhl.com ship-val-global-req.xsd" schemaVersion="10.0"
    xmlns:req="http://www.dhl.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
    <Request>
        <ServiceHeader>
            <MessageTime>{{ $data['date'] }}</MessageTime>
            <MessageReference>MightyApe_1578437_M-Test-S10p0</MessageReference>
            <SiteID>v62_mNH6PcS0Cp</SiteID>
            <Password>NFV561CHLc</Password>
        </ServiceHeader>
        <MetaData>
            <SoftwareName>ESANL</SoftwareName>
            <SoftwareVersion>10.0</SoftwareVersion>
        </MetaData>
    </Request>
    <RegionCode>{{ $data['regionCode'] }}</RegionCode>
    <RequestedPickupTime>N</RequestedPickupTime>
    <LanguageCode>en</LanguageCode>
    <Billing>
        <ShipperAccountNumber>{{ $data['accountNumber'] }}</ShipperAccountNumber>
        <ShippingPaymentType>S</ShippingPaymentType>
        <BillingAccountNumber>{{ $data['accountNumber'] }}</BillingAccountNumber>
    </Billing>
    <Consignee>
        <CompanyName>{{ $data['order']->Name . ' ' . $data['order']->Surname }}</CompanyName>
        <AddressLine1>{{ $data['order']->DAddress1 }}</AddressLine1>
        @if ($data['order']->DAddress2)
            <AddressLine2>{{ $data['order']->DAddress2 }}</AddressLine2>
        @endif
        <City>{{ $data['order']->DAddress3 }}</City>
        <!--<DivisionCode>New South Wales</DivisionCode>-->
        <PostalCode>{{ $data['order']->DPostcode }}</PostalCode>
        <CountryCode>{{ $data['order']->CountryCodeName }}</CountryCode>
        <CountryName>{{ $data['order']->CountryName }}</CountryName>
        <Contact>
            <PersonName>{{ $data['order']->Name . ' ' . $data['order']->Surname }}</PersonName>
            <PhoneNumber>35318746881</PhoneNumber>
            <Email>{{ $data['order']->Email }}</Email>
            <MobilePhoneNumber>3530864537340</MobilePhoneNumber>
        </Contact>
        <!--      <Suburb>This is test Suburb value  random</Suburb>-->
        <StreetName>MARGUERITE ROAD</StreetName>
        <BuildingName>GIOTTO TOWER</BuildingName>
        <StreetNumber>36A/P</StreetNumber>
        <RegistrationNumbers>
            <RegistrationNumber>
                <Number>977968896791291-134342-12319-121239</Number>
                <NumberTypeCode>RGP</NumberTypeCode>
                <NumberIssuerCountryCode>GB</NumberIssuerCountryCode>
            </RegistrationNumber>
        </RegistrationNumbers>
        <BusinessPartyTypeCode>PR</BusinessPartyTypeCode>
    </Consignee>
    <Dutiable>
        <DeclaredValue>72.025</DeclaredValue>
        <DeclaredCurrency>EUR</DeclaredCurrency>
        <ShipperEIN>ShipperEIN</ShipperEIN>
        <TermsOfTrade>DAP</TermsOfTrade>
    </Dutiable>
    <UseDHLInvoice>Y</UseDHLInvoice>
    <DHLInvoiceLanguageCode>en</DHLInvoiceLanguageCode>
    <DHLInvoiceType>CMI</DHLInvoiceType>
    <ExportDeclaration>
        <InterConsignee>Cosg32424</InterConsignee>
        <IsPartiesRelation>N</IsPartiesRelation>
        <SignatureName>SignatureName</SignatureName>
        <SignatureTitle>SignatureTitle</SignatureTitle>
        <ExportReason>ExportReason</ExportReason>
        <ExportReasonCode>P</ExportReasonCode>
        <SedNumber>XTN</SedNumber>
        <SedNumberType>I</SedNumberType>
        <InvoiceNumber>MyDHLAPI - INV-001</InvoiceNumber>
        <InvoiceDate>{{ $data['invoice_date'] }}</InvoiceDate>
        <!--BillToCompanyName>JUDY CO. Name</BillToCompanyName>
  <BillToContanctName>JUDY MELENDEZ</BillToContanctName>
  <BillToAddressLine1>Billing Invoice Address Line 1 test lengths12</BillToAddressLine1>
  <BillToAddressLine2>Billing Invoice Address Line 2 test lengths12</BillToAddressLine2>
  <BillToAddressLine3>Billing Invoice Address Line 3 test lengths12</BillToAddressLine3>
  <BillToCity>YOUGHAL</BillToCity>
  <BillToPostcode>00000</BillToPostcode>
  <BillToState>NSW</BillToState>
  <BillToCountryCode>IE</BillToCountryCode>
  <BillToCountryName>IRELAND, REPUBLIC OF</BillToCountryName>
  <BillToPhoneNumber>35316781250</BillToPhoneNumber>
  <BillToPhoneNumberExtn>3622</BillToPhoneNumberExtn>
  <BillToFaxNumber>1837</BillToFaxNumber>
  <BillToFederalTaxID>Billing Invoice Federal Tax ID</BillToFederalTaxID>
  <BillToStreetName>GLASNEVIN ROAD</BillToStreetName>
  <BillToBuildingName>COLONIA TOWER</BillToBuildingName>
  <BillToStreetNumber>36/21C</BillToStreetNumber>
  <BillToEmail>JUDY.MELENDEZ@GMAIL.COM</BillToEmail>
  <BillToRegistrationNumbers>
   <RegistrationNumber>
    <Number>555968896791291-134342-12319-121239</Number>
    <NumberTypeCode>VAT</NumberTypeCode>
    <NumberIssuerCountryCode>IE</NumberIssuerCountryCode>
   </RegistrationNumber>
  </BillToRegistrationNumbers>
  <BillToBusinessPartyTypeCode>PR</BillToBusinessPartyTypeCode-->
        <Remarks>Invoice Remarks</Remarks>
        <OtherCharges>
            <OtherCharge>
                <OtherChargeCaption>Freight Charges</OtherChargeCaption>
                <OtherChargeValue>10.00</OtherChargeValue>
                <OtherChargeType>FRCST</OtherChargeType>
            </OtherCharge>
            <OtherCharge>
                <OtherChargeCaption>OthersCharge</OtherChargeCaption>
                <OtherChargeValue>7.25</OtherChargeValue>
                <OtherChargeType>SOTHR</OtherChargeType>
            </OtherCharge>
            <OtherCharge>
                <OtherChargeCaption>InsuranceCharge</OtherChargeCaption>
                <OtherChargeValue>11.00</OtherChargeValue>
                <OtherChargeType>INSCH</OtherChargeType>
            </OtherCharge>
            <OtherCharge>
                <OtherChargeCaption>ReverseCharge</OtherChargeCaption>
                <OtherChargeValue>7.25</OtherChargeValue>
                <OtherChargeType>REVCH</OtherChargeType>
            </OtherCharge>
        </OtherCharges>
        <TermsOfPayment>credit card</TermsOfPayment>
        <PayerGSTVAT>yes</PayerGSTVAT>
        <SignatureImage>
            {{ $data['signature'] }}
        </SignatureImage>
        <ReceiverReference>ReceiverReference</ReceiverReference>
        <ExporterId>43244325</ExporterId>
        <ExporterCode>ExporterCode</ExporterCode>
        <PackageMarks>PackageMarks</PackageMarks>
        <OtherRemarks2>OtherRemarks2</OtherRemarks2>
        <OtherRemarks3>OtherRemarks3</OtherRemarks3>
        <AddDeclText1>It is declared that this shipment does not include any solid wood packing materials.These items
            are controlled by the U.S. Government and authorized for export only to the country of ultimate destination
            for use by the ultimate consignee or end-user(s) herein identified.</AddDeclText1>
        <AddDeclText2>I DECLARE ALL INFORMATION TRUE AND CORRECT</AddDeclText2>
        <AddDeclText3>AddDeclText3</AddDeclText3>
        <ExportLineItem>
            <LineNumber>1</LineNumber>
            <Quantity>{{ $data['product']->Quantity }}</Quantity>
            <QuantityUnit>PCS</QuantityUnit>
            <Description>{{ $data['product']->Description }}</Description>
            <Value>36.525</Value>
            <IsDomestic>Y</IsDomestic>
            <CommodityCode>4202.1260</CommodityCode>
            <ScheduleB>ScheduleB</ScheduleB>
            <ECCN>0323434422</ECCN>
            <Weight>
                <Weight>3.25</Weight>
                <WeightUnit>K</WeightUnit>
            </Weight>
            <GrossWeight>
                <Weight>3.33</Weight>
                <WeightUnit>K</WeightUnit>
            </GrossWeight>
            <License>
                <LicenseNumber>123</LicenseNumber>
                <ExpiryDate>2023-12-10</ExpiryDate>
            </License>
            <LicenseSymbol>LICENSE</LicenseSymbol>
            <ManufactureCountryCode>TH</ManufactureCountryCode>
            {{-- <AdditionalInformation>
                <AdditionalInformationText>EDLN 1st item - additional information line 1</AdditionalInformationText>
                <AdditionalInformationText>EDLN 2nd item - additional information line 1</AdditionalInformationText>
            </AdditionalInformation> --}}
            <ImportCommodityCode>6211.12.0000</ImportCommodityCode>
            <ItemReferences>
                <ItemReference>
                    <ItemReferenceType>AFE</ItemReferenceType>
                    <ItemReferenceNumber>AFE-1299210554413</ItemReferenceNumber>
                </ItemReference>
                <ItemReference>
                    <ItemReferenceType>PAN</ItemReferenceType>
                    <ItemReferenceNumber>12140007</ItemReferenceNumber>
                </ItemReference>
                <ItemReference>
                    <ItemReferenceType>DTC</ItemReferenceType>
                    <ItemReferenceNumber>Test-129921054413</ItemReferenceNumber>
                </ItemReference>
            </ItemReferences>
            <CustomsPaperworks>
                <CustomsPaperwork>
                    <CustomsPaperworkType>INV</CustomsPaperworkType>
                    <CustomsPaperworkID>MyDHLAPI - LN#1-CUSDOC-001</CustomsPaperworkID>
                </CustomsPaperwork>
            </CustomsPaperworks>
        </ExportLineItem>
        <InvoiceInstructions>This is invoice instruction</InvoiceInstructions>
        <CustomerDataTextEntries>
            <CustomerDataTextEntry>
                <CustomerDataTextNumber>1</CustomerDataTextNumber>
                <CustomerDataText>Customer Data Text Line - First</CustomerDataText>
            </CustomerDataTextEntry>
            <CustomerDataTextEntry>
                <CustomerDataTextNumber>2</CustomerDataTextNumber>
                <CustomerDataText>Customer Data Text Line - Second</CustomerDataText>
            </CustomerDataTextEntry>
        </CustomerDataTextEntries>
        <PlaceOfIncoterm>DUBLIN PORT</PlaceOfIncoterm>
        <ShipmentPurpose>COMMERCIAL</ShipmentPurpose>
        <CustomsDocuments>
            <CustomsDocument>
                <CustomsDocumentType>INV</CustomsDocumentType>
                <CustomsDocumentID>MyDHLAPI - CUSDOC-001</CustomsDocumentID>
            </CustomsDocument>
        </CustomsDocuments>
        <InvoiceTotalNetWeight>7.800</InvoiceTotalNetWeight>
        <InvoiceTotalGrossWeight>8.250</InvoiceTotalGrossWeight>
        <InvoiceReferences>
            <InvoiceReference>
                <InvoiceReferenceType>PON</InvoiceReferenceType>
                <InvoiceReferenceNumber>MyDHLAPI - PONREF-001</InvoiceReferenceNumber>
            </InvoiceReference>
            <InvoiceReference>
                <InvoiceReferenceType>OID</InvoiceReferenceType>
                <InvoiceReferenceNumber>MyDHLAPI - OIDREF-002</InvoiceReferenceNumber>
            </InvoiceReference>
            <InvoiceReference>
                <InvoiceReferenceType>RMA</InvoiceReferenceType>
                <InvoiceReferenceNumber>Test-78398974</InvoiceReferenceNumber>
            </InvoiceReference>
        </InvoiceReferences>
    </ExportDeclaration>
    <!--Reference>
      <ReferenceID>ShipLevelTest2</ReferenceID>
      <ReferenceType>OBC</ReferenceType>
   </Reference-->
    <ShipmentDetails>
        <Pieces>
            <Piece>
                <PieceID>1</PieceID>
                <PackageType>EE</PackageType>
                <Weight>1.5</Weight>
                <Width>11</Width>
                <Height>4</Height>
                <Depth>2</Depth>
                <PieceContents>1st Piece Content</PieceContents>
                <PieceReference>
                    <ReferenceID>Piece1Reference</ReferenceID>
                    <ReferenceType>AAO</ReferenceType>
                </PieceReference>
            </Piece>
        </Pieces>
        <WeightUnit>K</WeightUnit>
        <GlobalProductCode>N</GlobalProductCode>
        <LocalProductCode>N</LocalProductCode>
        <Date>{{ $data['shipment_date'] }}</Date>
        <Contents>FST-Test_CI-Mask_1p24-Schema10</Contents>
        <DimensionUnit>C</DimensionUnit>
        <PackageType>EE</PackageType>
        <IsDutiable>Y</IsDutiable>
        <CurrencyCode>EUR</CurrencyCode>
    </ShipmentDetails>
    <Shipper>
        <ShipperID>{{ $data['accountNumber'] }}</ShipperID>
        <CompanyName>{{ $data['shipper']->CompanyName }}</CompanyName>
        <AddressLine1>{{ $data['shipper']->Address1 }}</AddressLine1>
        @if ($data['shipper']->Address2)
            <AddressLine2>{{ $data['shipper']->Address2 }}</AddressLine2>
        @endif
        @if ($data['shipper']->Address3)
            <AddressLine3>{{ $data['shipper']->Address3 }}</AddressLine3>
        @endif
        <City>{{ $data['shipper']->Address4 }}</City>
        <PostalCode>{{ $data['shipper']->Postcode }}</PostalCode>
        <CountryCode>{{ $data['shipper']->CountryCodeName }}</CountryCode>
        <CountryName>{{ $data['shipper']->CountryName }}</CountryName>
        <Contact>
            <PersonName>Dispatch</PersonName>
            <PhoneNumber>{{ $data['shipper']->Telephone }}</PhoneNumber>
            <Email>pragnesh@goodcareit.com</Email>
        </Contact>
        <!--      <Suburb>CN123</Suburb>-->
        <StreetName>BOUSTEAD ROAD</StreetName>
        <BuildingName>VELASCA TOWER</BuildingName>
        <StreetNumber>46A/26</StreetNumber>
        <RegistrationNumbers>
            <RegistrationNumber>
                <Number>{{ $data['vat'] }}</Number>
                <NumberTypeCode>VAT</NumberTypeCode>
                <NumberIssuerCountryCode>{{ $data['shipper']->CountryCodeName }}</NumberIssuerCountryCode>
            </RegistrationNumber>
        </RegistrationNumbers>
        <BusinessPartyTypeCode>BU</BusinessPartyTypeCode>
    </Shipper>
   <SpecialService>
        <SpecialServiceType>WY</SpecialServiceType>
    </SpecialService>    
    <Notification>
        <EmailAddress>test@aa.com</EmailAddress>
        <Message>This is additional message</Message>
    </Notification>
    <EProcShip>N</EProcShip>
    <DocImages>
        <DocImage>
            <Type>COO</Type>
            <Image>
                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NyAwIDcyMiA2NjcgNjExIDcyMiAwIDAgMCAwIDAgMCAwIDAgMCA1NTYKMCA1NTYgNTU2IDUwMCA1NTYgNTU2IDI3OCA1NTYgNTU2IDIyMiAwIDAgMjIyIDgzMyA1NTYgNTU2CjU1NiAwIDMzMyA1MDAgMjc4IDU1NiA1MDAgMCAwIDUwMF0KL0VuY29kaW5nL1dpbkFuc2lFbmNvZGluZy9TdWJ0eXBlL1R5cGUxPj4KZW5kb2JqCjEzIDAgb2JqCjw8L0Jhc2VGb250L1ZSQ0JBVCtUVEUyM0RFOTk4dDAwL0ZvbnREZXNjcmlwdG9yIDEyIDAgUi9UeXBlL0ZvbnQKL0ZpcnN0Q2hhciAxL0xhc3RDaGFyIDI3L1dpZHRoc1sgNTU4IDUyNSA1NTggNTUzIDIyOSA1MjYgMzEzIDIyOSAzMzQgNzQyIDQ2MSAzNjAgMzYzIDMxOCA1NTMKNDQ2IDU0NiA4NDAgNTQ2IDU0NiA1NTEgNTQzIDYwMSA1NTMgNDk4IDM1NCA1NThdCi9FbmNvZGluZyAzMiAwIFIvU3VidHlwZS9UcnVlVHlwZT4+CmVuZG9iagozMiAwIG9iago8PC9UeXBlL0VuY29kaW5nL0Jhc2VFbmNvZGluZy9XaW5BbnNpRW5jb2RpbmcvRGlmZmVyZW5jZXNbCjEvaC9hL24vZC9sL2Uvc3BhY2UvaS90L3cvYy9yL2h5cGhlbi9mL2cvcy9vbmUvbS90d28vdGhyZWUvUC9vL0MvcC95L2NvbG9uL3VdPj4KZW5kb2JqCjkgMCBvYmoKPDwvQmFzZUZvbnQvRUxBSEpMK0hlbHZldGljYS1Cb2xkL0ZvbnREZXNjcmlwdG9yIDggMCBSL1R5cGUvRm9udAovRmlyc3RDaGFyIDMyL0xhc3RDaGFyIDEyMS9XaWR0aHNbCjI3OCAwIDAgMCAwIDAgMCAwIDAgMCAwIDAgMCAwIDI3OCAyNzgKMCAwIDAgMCAwIDAgMCAwIDAgMCAzMzMgMCAwIDAgMCAwCjAgNzIyIDcyMiA3MjIgNzIyIDY2NyA2MTEgNzc4IDcyMiAyNzggMCAwIDAgODMzIDcyMiAwCjY2NyA3NzggNzIyIDY2NyA2MTEgNzIyIDY2NyA5NDQgMCA2NjcgMCAwIDAgMCAwIDU1NgowIDU1NiA2MTEgNTU2IDYxMSA1NTYgMzMzIDYxMSA2MTEgMjc4IDAgNTU2IDI3OCA4ODkgNjExIDYxMQo2MTEgMCAzODkgNTU2IDMzMyA2MTEgNTU2IDAgNTU2IDU1Nl0KL0VuY29kaW5nL1dpbkFuc2lFbmNvZGluZy9TdWJ0eXBlL1R5cGUxPj4KZW5kb2JqCjEwIDAgb2JqCjw8L1R5cGUvRm9udERlc2NyaXB0b3IvRm9udE5hbWUvQ1lPWVNYK0hlbHZldGljYS9Gb250QkJveFstMjIgLTIxOCA3NjIgNzQxXS9GbGFncyA0Ci9Bc2NlbnQgNzQxCi9DYXBIZWlnaHQgNzQxCi9EZXNjZW50IC0yMTgKL0l0YWxpY0FuZ2xlIDAKL1N0ZW1WIDExNAovTWlzc2luZ1dpZHRoIDI3OAovQ2hhclNldCgvdHdvL0wvQS95L24vYy90aHJlZS9NL0Ivby9kL2ZvdXIvTi9DL3AvZS9maXZlL08vRC9mL3NpeC9QL0Uvci9nL3NldmVuL0Yvcy9oL2VpZ2h0L1IvRy90L2kvbmluZS9TL0gvdS91bmRlcnNjb3JlL2NvbG9uL1QvSS92L1UvbC9hL20vYi9zcGFjZS9jb21tYS9wZXJpb2QvemVyby9vbmUpL0ZvbnRGaWxlMyAyNiAwIFI+PgplbmRvYmoKMTIgMCBvYmoKPDwvVHlwZS9Gb250RGVzY3JpcHRvci9Gb250TmFtZS9WUkNCQVQrVFRFMjNERTk5OHQwMC9Gb250QkJveFswIC0yMDYgODc1IDc2NF0vRmxhZ3MgNAovQXNjZW50IDc2NAovQ2FwSGVpZ2h0IDc2NAovRGVzY2VudCAtMjA2Ci9JdGFsaWNBbmdsZSAwCi9TdGVtViAxMzEKL01pc3NpbmdXaWR0aCAxMDAwCi9Gb250RmlsZTIgMjggMCBSPj4KZW5kb2JqCjggMCBvYmoKPDwvVHlwZS9Gb250RGVzY3JpcHRvci9Gb250TmFtZS9FTEFISkwrSGVsdmV0aWNhLUJvbGQvRm9udEJCb3hbLTIyIC0yMTkgOTMyIDc0MV0vRmxhZ3MgNAovQXNjZW50IDc0MQovQ2FwSGVpZ2h0IDc0MQovRGVzY2VudCAtMjE5Ci9JdGFsaWNBbmdsZSAwCi9TdGVtViAxMzkKL01pc3NpbmdXaWR0aCAyNzgKL0NoYXJTZXQoL0EveS9uL2MvTS9CL28vZC9ZL04vQy9wL2UvRC9mL1AvRS9yL2cvUS9GL3MvaC9SL0cvdC9pL1MvSC91L3VuZGVyc2NvcmUvY29sb24vVC9JL3Yvay9VL2wvYS9WL3gvbS9iL1cvc3BhY2UvcGVyaW9kL3NsYXNoKS9Gb250RmlsZTMgMzAgMCBSPj4KZW5kb2JqCjIgMCBvYmoKPDwvUHJvZHVjZXIoR1BMIEdob3N0c2NyaXB0IDguMTUpCi9DcmVhdGlvbkRhdGUoRDoyMDEyMDkwNDIyMDE1MSkKL01vZERhdGUoRDoyMDEyMDkwNDIyMDE1MSkKL1RpdGxlKFByb2Zvcm1hKQovQ3JlYXRvcihQU2NyaXB0NS5kbGwgVmVyc2lvbiA1LjIuMikKL0F1dGhvcihtYmxhbmcpPj5lbmRvYmoKeHJlZgowIDMzCjAwMDAwMDAwMDAgNjU1MzUgZiAKMDAwMDAxMDMwMyAwMDAwMCBuIAowMDAwMDI4OTU0IDAwMDAwIG4gCjAwMDAwMTAyMjEgMDAwMDAgbiAKMDAwMDAwOTczNyAwMDAwMCBuIAowMDAwMDAwMDE1IDAwMDAwIG4gCjAwMDAwMDMxOTIgMDAwMDAgbiAKMDAwMDAxMDM1MSAwMDAwMCBuIAowMDAwMDI4NjE1IDAwMDAwIG4gCjAwMDAwMjc1OTggMDAwMDAgbiAKMDAwMDAyODAzMCAwMDAwMCBuIAowMDAwMDI2NzQzIDAwMDAwIG4gCjAwMDAwMjg0MDcgMDAwMDAgbiAKMDAwMDAyNzE4NCAwMDAwMCBuIAowMDAwMDEwMzkyIDAwMDAwIG4gCjAwMDAwMTA0MjIgMDAwMDAgbiAKMDAwMDAwOTg5NyAwMDAwMCBuIAowMDAwMDAzMjEyIDAwMDAwIG4gCjAwMDAwMDYzNTEgMDAwMDAgbiAKMDAwMDAxMDQ3NCAwMDAwMCBuIAowMDAwMDEwNTA0IDAwMDAwIG4gCjAwMDAwMTAwNTkgMDAwMDAgbiAKMDAwMDAwNjM3MiAwMDAwMCBuIAowMDAwMDA5NzE2IDAwMDAwIG4gCjAwMDAwMTA1NTYgMDAwMDAgbiAKMDAwMDAxMDU4NiAwMDAwMCBuIAowMDAwMDEwNjM4IDAwMDAwIG4gCjAwMDAwMTQ1NzMgMDAwMDAgbiAKMDAwMDAxNDU5NCAwMDAwMCBuIAowMDAwMDIzNDI5IDAwMDAwIG4gCjAwMDAwMjM0NTAgMDAwMDAgbiAKMDAwMDAyNjcyMiAwMDAwMCBuIAowMDAwMDI3NDQzIDAwMDAwIG4gCnRyYWlsZXIKPDwgL1NpemUgMzMgL1Jvb3QgMSAwIFIgL0luZm8gMiAwIFIKL0lEIFso2eBcbkJsMtst6i+tAntCoXgpKNngXG5CbDLbLeovrQJ7QqF4KV0KPj4Kc3RhcnR4cmVmCjI5MTMzCiUlRU9GCg==
            </Image>
            <ImageFormat>JPEG</ImageFormat>
        </DocImage>
    </DocImages>
    <LabelImageFormat>PDF</LabelImageFormat>
    <RequestArchiveDoc>Y</RequestArchiveDoc>
    <NumberOfArchiveDoc>1</NumberOfArchiveDoc>
    <Label>
        <HideAccount>Y</HideAccount>
        <LabelTemplate>8X4_PDF</LabelTemplate>
        <CustomsInvoiceTemplate>COMMERCIAL_INVOICE_L_10</CustomsInvoiceTemplate>
        <Logo>Y</Logo>
        <CustomerLogo>
            <LogoImage>
                {{ $data['logo'] }}
            </LogoImage>
            <LogoImageFormat>JPG</LogoImageFormat>
        </CustomerLogo>
    </Label>
    <GetPriceEstimate>N</GetPriceEstimate>
    <SinglePieceImage>N</SinglePieceImage>
    <Importer>
        <CompanyName>DAVID CO. Name</CompanyName>
        <AddressLine1>19th Floor, Plaza IBM</AddressLine1>
        <AddressLine2>No. 8, First Avenue,</AddressLine2>
        <AddressLine3>Kingsford Drive</AddressLine3>
        <City>Hounslow</City>
        <PostalCode>TW4 6JS</PostalCode>
        <CountryCode>GB</CountryCode>
        <CountryName>United Kingdom</CountryName>
        <Contact>
            <PersonName>DAVID GOW</PersonName>
            <PhoneNumber>353 1 235 2369</PhoneNumber>
            <PhoneExtension>45232</PhoneExtension>
            <FaxNumber>11234325423</FaxNumber>
            <Telex>454586</Telex>
            <Email>DAVID.GOW@GMAIL.COM</Email>
            <MobilePhoneNumber>8978967878</MobilePhoneNumber>
        </Contact>
        <StreetName>GLASNEVIN ROAD</StreetName>
        <BuildingName>BOSCO TOWER</BuildingName>
        <StreetNumber>10C/A</StreetNumber>
        <RegistrationNumbers>
            <RegistrationNumber>
                <Number>IP-VAT-001</Number>
                <NumberTypeCode>VAT</NumberTypeCode>
                <NumberIssuerCountryCode>GB</NumberIssuerCountryCode>
            </RegistrationNumber>
            <RegistrationNumber>
                <Number>IP-DAN-001</Number>
                <NumberTypeCode>DAN</NumberTypeCode>
                <NumberIssuerCountryCode>GB</NumberIssuerCountryCode>
            </RegistrationNumber>
        </RegistrationNumbers>
        <BusinessPartyTypeCode>BU</BusinessPartyTypeCode>
    </Importer>
    <Exporter>
        <CompanyName>THOMAS CO. Name</CompanyName>
        <SuiteDepartmentName>aabc 453</SuiteDepartmentName>
        <AddressLine1>Exp Ad Ln1, Rounding Off Address 10003 St Cor</AddressLine1>
        <AddressLine2>Exp Ad Ln2, Spin off Drive Privet St, Bern Ci</AddressLine2>
        <AddressLine3>Exp Ad Ln3, ty Province of Plateness, 2900011</AddressLine3>
        <City>QUITAQUE</City>
        <PostalCode>79255</PostalCode>
        <CountryCode>US</CountryCode>
        <CountryName>UNITED STATE</CountryName>
        <Contact>
            <PersonName>THOMAS PEDERSEN</PersonName>
            <PhoneNumber>17243557355</PhoneNumber>
            <PhoneExtension>75495</PhoneExtension>
            <FaxNumber>4232094870</FaxNumber>
            <Telex>0080</Telex>
            <Email>THOMAS.PEDERSEN@GMAIL.COM</Email>
            <MobilePhoneNumber>4325466664325</MobilePhoneNumber>
        </Contact>
        <StreetName>MAHSURI ROAD</StreetName>
        <BuildingName>RADISSON TOWER</BuildingName>
        <StreetNumber>10/36A</StreetNumber>
        <RegistrationNumbers>
            <RegistrationNumber>
                <Number>233968896791291-134342-12319-121239</Number>
                <NumberTypeCode>VAT</NumberTypeCode>
                <NumberIssuerCountryCode>US</NumberIssuerCountryCode>
            </RegistrationNumber>
        </RegistrationNumbers>
        <BusinessPartyTypeCode>BU</BusinessPartyTypeCode>
    </Exporter>
    <Seller>
        <CompanyName>SL CompanyName</CompanyName>
        <SuiteDepartmentName>SL STDepartName</SuiteDepartmentName>
        <AddressLine1>SL AddressLine1</AddressLine1>
        <AddressLine2>SL AddressLine2</AddressLine2>
        <AddressLine3>SL AddressLine3</AddressLine3>
        <City>YONKERS</City>
        <Division>Yonkerss</Division>
        <DivisionCode>YK</DivisionCode>
        <PostalCode>10705</PostalCode>
        <CountryCode>US</CountryCode>
        <CountryName>United States of America</CountryName>
        <Contact>
            <PersonName>SL PersonName</PersonName>
            <PhoneNumber>111212133</PhoneNumber>
            <PhoneExtension>22212</PhoneExtension>
            <FaxNumber>1111</FaxNumber>
            <Telex>4444</Telex>
            <Email>sl_nonreply@dhl.com</Email>
            <MobilePhoneNumber>3321223124</MobilePhoneNumber>
        </Contact>
        <Suburb>ny</Suburb>
        <StreetName>SL-CI 1/24</StreetName>
        <BuildingName>SL BuilName</BuildingName>
        <StreetNumber>SL StrName</StreetNumber>
        <RegistrationNumbers>
            <RegistrationNumber>
                <Number>SL-1111111</Number>
                <NumberTypeCode>VAT</NumberTypeCode>
                <NumberIssuerCountryCode>US</NumberIssuerCountryCode>
            </RegistrationNumber>
        </RegistrationNumbers>
        <BusinessPartyTypeCode>BU</BusinessPartyTypeCode>
    </Seller>
    <Payer>
        <CompanyName>PY CompanyName</CompanyName>
        <SuiteDepartmentName>PY SuiteDepartname</SuiteDepartmentName>
        <AddressLine1>PY AddressLine1</AddressLine1>
        <AddressLine2>PY AddressLine2</AddressLine2>
        <AddressLine3>PY AddressLine2</AddressLine3>
        <City>LONDON</City>
        <Division>LOD</Division>
        <DivisionCode>LD</DivisionCode>
        <PostalCode>E1 6AN</PostalCode>
        <CountryCode>GB</CountryCode>
        <CountryName>United Kingdom</CountryName>
        <Contact>
            <PersonName>PY PersonName</PersonName>
            <PhoneNumber>11234325423</PhoneNumber>
            <PhoneExtension>1111</PhoneExtension>
            <FaxNumber>1123111312</FaxNumber>
            <Telex>123123</Telex>
            <Email>py@nonreply@dhl.com</Email>
            <MobilePhoneNumber>12312312312</MobilePhoneNumber>
        </Contact>
        <Suburb>London</Suburb>
        <StreetName>PY StrName</StreetName>
        <BuildingName>PY BuilName</BuildingName>
        <StreetNumber>PY-CI 1/24</StreetNumber>
        <RegistrationNumbers>
            <RegistrationNumber>
                <Number>PY-2222222</Number>
                <NumberTypeCode>VAT</NumberTypeCode>
                <NumberIssuerCountryCode>GB</NumberIssuerCountryCode>
            </RegistrationNumber>
        </RegistrationNumbers>
        <BusinessPartyTypeCode>PR</BusinessPartyTypeCode>
    </Payer>
</req:ShipmentRequest>
