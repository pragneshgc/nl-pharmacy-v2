<req:ShipmentRequest xsi:schemaLocation="http://www.dhl.com ship-val-global-req.xsd" schemaVersion="10.0" xmlns:req="http://www.dhl.com" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance">
   <Request>
      <ServiceHeader>
         <MessageTime>{{ $data['date'] }}</MessageTime>
         <MessageReference>1234567890123456789012345678901</MessageReference>
         <SiteID>v62_mNH6PcS0Cp</SiteID>
         <Password>NFV561CHLc</Password>
      </ServiceHeader>
      <MetaData>
         <SoftwareName>test</SoftwareName>
         <SoftwareVersion>10.0</SoftwareVersion>
      </MetaData>
   </Request>
   <RegionCode>{{ $data['regionCode'] }}</RegionCode>
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
      <AddressLine3>{{ $data['order']->DAddress3 }}</AddressLine3>
      <City>{{ $data['order']->DAddress3 }}</City>
      <PostalCode>{{ $data['order']->DPostcode }}</PostalCode>
      <CountryCode>{{ $data['order']->CountryCodeName }}</CountryCode>
      <CountryName>{{ $data['order']->CountryName }}</CountryName>
      <Contact>
         <PersonName>{{ $data['order']->Name . ' ' . $data['order']->Surname }}</PersonName>
         <PhoneNumber>12172762192</PhoneNumber>
         <PhoneExtension/>
         <FaxNumber>0000</FaxNumber>
         <Email>{{ $data['order']->Email }}</Email>
         <MobilePhoneNumber>+2177979799</MobilePhoneNumber>
      </Contact>
     <StreetName>MARGUERITE ROAD</StreetName>
		<BuildingName>GIOTTO TOWER</BuildingName>
		<StreetNumber>36A/P</StreetNumber>
     <RegistrationNumbers>
         <RegistrationNumber>
            <Number>97796889679</Number>
            <NumberTypeCode>VAT</NumberTypeCode>
            <NumberIssuerCountryCode>GB</NumberIssuerCountryCode>
         </RegistrationNumber>
         <RegistrationNumber>
            <Number>977961111</Number>
            <NumberTypeCode>EOR</NumberTypeCode>
            <NumberIssuerCountryCode>GB</NumberIssuerCountryCode>
         </RegistrationNumber>
      </RegistrationNumbers>
      <BusinessPartyTypeCode>BU</BusinessPartyTypeCode>
   </Consignee>
   <Dutiable>
      <DeclaredValue>113.225</DeclaredValue>
      <DeclaredCurrency>EUR</DeclaredCurrency>
      <ShipperEIN>ShipperEIN12023</ShipperEIN>
      <TermsOfTrade>DAP</TermsOfTrade>
   </Dutiable>
   <UseDHLInvoice>Y</UseDHLInvoice>
   <DHLInvoiceLanguageCode>en</DHLInvoiceLanguageCode>
   <DHLInvoiceType>CMI</DHLInvoiceType>
   <ExportDeclaration>
      <InterConsignee>Cosg32424</InterConsignee>
      <IsPartiesRelation>N</IsPartiesRelation>
      <SignatureName>Riaz Vali</SignatureName>
      <SignatureTitle>Director</SignatureTitle>
      <ExportReason>Sale</ExportReason>
      <ExportReasonCode>P</ExportReasonCode>   
      <InvoiceNumber>MyDHLAPI - INV-001</InvoiceNumber>
      <InvoiceDate>{{ $data['invoice_date'] }}</InvoiceDate>
      <Remarks>Invoice Remarks</Remarks>
      <OtherCharges>
         <OtherCharge>
            <OtherChargeCaption>Freight Charges</OtherChargeCaption>
            <OtherChargeValue>12.50</OtherChargeValue>
            <OtherChargeType>FRCST</OtherChargeType>
         </OtherCharge>
         <OtherCharge>
				<OtherChargeCaption>Handlingcare</OtherChargeCaption>
				<OtherChargeValue>7.25</OtherChargeValue>
				<OtherChargeType>HRCRG</OtherChargeType>
			</OtherCharge>
      </OtherCharges>
      <TermsOfPayment>60 days</TermsOfPayment>
      <PayerGSTVAT>yes</PayerGSTVAT>
      <SignatureImage>{{ $data['signature'] }}</SignatureImage>
      <ReceiverReference>ReceiverReference</ReceiverReference>
      <ExporterId>43244325</ExporterId>
      <ExporterCode>ExporterCode</ExporterCode>
      <PackageMarks>PackageMarks</PackageMarks>
      <OtherRemarks2>OtherRemarks2</OtherRemarks2>
      <OtherRemarks3>OtherRemarks3</OtherRemarks3>
      <AddDeclText1>
         The exporter of the products covered by this document (EORI: GB275673564000) declares that, except where otherwise clearly indicated, these products are of United Kingdom preferential origin.    
      </AddDeclText1>
      <AddDeclText2>
         (Bolton, United Kingdom, {{ date("Y-m-d") }})
         (HR Healthcare Ltd)
      </AddDeclText2>
      <ExportLineItem>
         <LineNumber>1</LineNumber>
         <Quantity>{{ $data['product']->Quantity }}</Quantity>
         <QuantityUnit>PCS</QuantityUnit>
         <Description>{{ $data['product']->Description }}</Description>
         <Value>56.525</Value>
         <CommodityCode>6109.10.0010</CommodityCode>
         <Weight>
            <Weight>0.200</Weight>
            <WeightUnit>K</WeightUnit>
         </Weight>
         <GrossWeight>
            <Weight>0.250</Weight>
            <WeightUnit>K</WeightUnit>
         </GrossWeight>
         <ManufactureCountryCode>US</ManufactureCountryCode>
         <ImportCommodityCode>6109.10.0010</ImportCommodityCode>
         <ItemReferences>
            <ItemReference>
               <ItemReferenceType>PAN</ItemReferenceType>
               <ItemReferenceNumber>10597122</ItemReferenceNumber>
            </ItemReference>
            <ItemReference>
               <ItemReferenceType>AFE</ItemReferenceType>
               <ItemReferenceNumber>105972112200</ItemReferenceNumber>
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
      <PlaceOfIncoterm>GAILDORF PORT</PlaceOfIncoterm>
      <ShipmentPurpose>COMMERCIAL</ShipmentPurpose>
      <DocumentFunction>IMPORT</DocumentFunction>
      <CustomsDocuments>
         <CustomsDocument>
            <CustomsDocumentType>INV</CustomsDocumentType>
            <CustomsDocumentID>MyDHLAPI - CUSDOC-001</CustomsDocumentID>
         </CustomsDocument>
      </CustomsDocuments>
      <InvoiceTotalNetWeight>0.400</InvoiceTotalNetWeight>
      <InvoiceTotalGrossWeight>0.500</InvoiceTotalGrossWeight>
      <InvoiceReferences>
         <InvoiceReference>
            <InvoiceReferenceType>OID</InvoiceReferenceType>
            <InvoiceReferenceNumber>OID-7839749374</InvoiceReferenceNumber>
         </InvoiceReference>
         <InvoiceReference>
            <InvoiceReferenceType>UCN</InvoiceReferenceType>
            <InvoiceReferenceNumber>UCN-76498376498</InvoiceReferenceNumber>
         </InvoiceReference>
         <InvoiceReference>
            <InvoiceReferenceType>CU</InvoiceReferenceType>
            <InvoiceReferenceNumber>MyDHLAPI - CUREF-001</InvoiceReferenceNumber>
         </InvoiceReference>
      </InvoiceReferences>
   </ExportDeclaration>
   <Reference>
      <ReferenceID>ShipLevelTest2</ReferenceID>
      <ReferenceType>OBC</ReferenceType>
   </Reference>
   <ShipmentDetails>
      <Pieces>
         <Piece>
            <PieceID>1</PieceID>
            <PackageType>EE</PackageType>
            <Weight>0.250</Weight>
            <Width>20</Width>
            <Height>15</Height>
            <Depth>30</Depth>
            <PieceContents>1st Piece clothing</PieceContents>
            <PieceReference>
               <ReferenceID>Piece1Reference</ReferenceID>
               <ReferenceType>PK2</ReferenceType>
            </PieceReference>
         </Piece>
         <Piece>
            <PieceID>2</PieceID>
            <PackageType>YP</PackageType>
            <Weight>0.250</Weight>
            <Width>20</Width>
            <Height>10</Height>
            <Depth>10</Depth>
            <PieceContents>shirt</PieceContents>
            <PieceReference>
               <ReferenceID>Piece2Reference</ReferenceID>
               <ReferenceType>PK2</ReferenceType>
            </PieceReference>
         </Piece>
      </Pieces>
      <WeightUnit>K</WeightUnit>
      <GlobalProductCode>P</GlobalProductCode>
      <LocalProductCode>P</LocalProductCode>
      <Date>{{ $data['shipment_date'] }}</Date>
      <Contents>v10.0 001 - parcel with clothing</Contents>
      <DimensionUnit>C</DimensionUnit>
      <PackageType>PA</PackageType>
      <IsDutiable>Y</IsDutiable>
      <CurrencyCode>EUR</CurrencyCode>
   </ShipmentDetails>
   <Shipper>
      <ShipperID>{{ $data['accountNumber'] }}</ShipperID>
      <CompanyName>{{ $data['shipper']->CompanyName }}</CompanyName>
      <AddressLine1>Auerstrasse 82</AddressLine1>
      <AddressLine2>Auerstrasse</AddressLine2>
      <AddressLine3>Maladers</AddressLine3>
      <City>Maladers</City>
      <DivisionCode/>
      <PostalCode>7026</PostalCode>
      <CountryCode>CH</CountryCode>
      <CountryName>Switzerland</CountryName>
      <Contact>
			<PersonName>ANDREA OLIVIA</PersonName>
			<PhoneNumber>{{ $data['shipper']->Telephone }}</PhoneNumber>
			<PhoneExtension>6536</PhoneExtension>
			<FaxNumber>17456356365</FaxNumber>
			<Telex>74558</Telex>
			<Email>{{ $data['shipper']->Email }}</Email>
			<MobilePhoneNumber>13056483896</MobilePhoneNumber>
		</Contact>
		<StreetName>BOUSTEAD ROAD</StreetName>
		<BuildingName>VELASCA TOWER</BuildingName>
		<StreetNumber>46A/26</StreetNumber>
      <RegistrationNumbers>
         <RegistrationNumber>
            <Number>{{ $data['vat'] }}</Number>
            <NumberTypeCode>FED</NumberTypeCode>
            <NumberIssuerCountryCode>{{ $data['shipper']->CountryCodeName }}</NumberIssuerCountryCode>
         </RegistrationNumber>         
      </RegistrationNumbers>
      <BusinessPartyTypeCode>BU</BusinessPartyTypeCode>
   </Shipper>
   <SpecialService>
      <SpecialServiceType>DD</SpecialServiceType>
   </SpecialService>
   <EProcShip>N</EProcShip>
   <LabelImageFormat>PDF</LabelImageFormat>
   <RequestArchiveDoc>Y</RequestArchiveDoc>
   <NumberOfArchiveDoc>1</NumberOfArchiveDoc>
   <Label>
      <HideAccount>Y</HideAccount>
      <LabelTemplate>8X4_thermal</LabelTemplate>
      <CustomsInvoiceTemplate>COMMERCIAL_INVOICE_P_10</CustomsInvoiceTemplate>
      <Logo>Y</Logo>
      <CustomerLogo>
         <LogoImage>{{ $data['logo'] }}</LogoImage>
         <LogoImageFormat>JPG</LogoImageFormat>
      </CustomerLogo>
   </Label>
   <GetPriceEstimate>N</GetPriceEstimate>
   <SinglePieceImage>N</SinglePieceImage>
</req:ShipmentRequest>