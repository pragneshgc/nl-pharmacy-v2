import { ref, computed } from 'vue';
import { conformsTo, filter } from "lodash";

export default {
    setup() {
        
        const orderStatuses = {
            '9': 'SAFETYCHECK',
            '1': 'NEW',
            '2': 'APPROVED',
            '7': 'AWAITINGSHIPPED',
            '8': 'SHIPPED',
            '10': 'ONHOLD',
            '4': 'QUERIED',
            '12': 'QUERIEDDISPENSED',
            '13': 'QUERIEDNOTDISPENSED',
            '15': 'QUERIEDARCHIVED',
            '14': 'QUERIEDNOREPLY',
            '3': 'REJECTED',
            '6': 'CANCELLED',
            '5': 'POSTPONED',
            '11': 'CALL',
            '16': 'RETURNED',
            // '17': 'REDELIVERY'
        };

        const orderSubStatuses = {
            '101': 'Awaiting stock',
            '102': 'Awaiting customer expiry date confirmation',
            '103': 'Fridge item (To be processed on next available shipping day)',
            '104': 'Out of stock',
            '105': 'Incorrect XML details',
            '106': 'Other',
            '107': 'Postponed Shipping Request ',
            '108': 'Order Received After Courier Left',
            '61': 'Customer/Client cancelled order',
            '62': 'Duplicate XML',
            '63': 'Returned and Cancelled',
            '64': 'Product Out Of Stock',
            '66': 'Expiry date confirmation not received from customer',
            '67': 'Shipping Address Undeliverable',
            '68': 'Test Order',
            '69': 'Other',
            '630': 'Returned and Cancelled (No Response Received from Client/Customer)',
            '631': 'Returned and Cancelled (Order Not Suitable for Redelivery)',
            // '632': 'Returned and Cancelled (Redelivery Refused by Client/Customer)',
            '633': 'Returned and Cancelled (Multiple Redeliveries Attempted)',
            '634': 'Returned and Cancelled (Other)',
            '91': 'Product Name Mismatch',
            '160': 'Multiple Deliveries Attempted by Courier',
            '161': 'Incorrect/Incomplete Address',
            '162': 'Customer Refused Delivery',
            '163': 'Shipment Not Collected by Customer',
            '164': 'Other',
        };

        const orderStatusesOptions = [
                {
                    id: '9',
                    label: 'SAFETYCHECK',
                },
                {
                    id: '1',
                    label: 'NEW',
                },
                {
                    id: '2',
                    label: 'APPROVED',
                },
                {
                    id: '7',
                    label: 'AWAITINGSHIPPED',
                },
                {
                    id: '8',
                    label: 'SHIPPED',
                },
                {
                    id: '10',
                    label: 'ONHOLD',
                    isDefaultExpanded: false,
                    children: [ 
                        {
                            id: '101',
                            label: 'Awaiting stock',
                            customLabel: `ONHOLD - Awaiting stock`
                        }, 
                        {
                            id: '102',
                            label: 'Awaiting customer expiry date confirmation (MM/YYYY)',
                            customLabel: `ONHOLD - Awaiting customer expiry date confirmation`
                        },
                        {
                            id: '107',
                            label: 'Postponed Shipping Request (DD/MM/YYYY)',
                            customLabel: `ONHOLD - Postponed Shipping Request`,
                        },
                        {
                            id: '103',
                            label: 'Fridge item (To be processed on next available shipping day)',
                            customLabel: `ONHOLD - Fridge item`
                        }, 
                        {
                            id: '104',
                            label: 'Out of stock',
                            customLabel: `ONHOLD - Out of stock`
                        }, 
                        {
                            id: '105',
                            label: 'Incorrect XML details',
                            customLabel: `ONHOLD - Incorrect XML details`
                        },
                        {
                            id: '108',
                            label: 'Order Received After Courier Left',
                            customLabel: `ONHOLD - Order Received After Courier Left`
                        },
                        {
                            id: '106',
                            label: 'Other (Free Text)',
                            customLabel: `ONHOLD - Other`,
                            input: true,                            
                        },                        
                    ],
                }, 
                {
                    id: '4',
                    label: 'QUERIED',
                }, 
                {
                    id: '12',
                    label: 'QUERIEDDISPENSED',
                },
                {
                    id: '13',
                    label: 'QUERIEDNOTDISPENSED',
                },                
                {
                    id: '3',
                    label: 'REJECTED',
                },
                {
                    id: '6',
                    label: 'CANCELLED',
                    isDefaultExpanded: false,
                    children: [ 
                        {
                            id: '61',
                            label: 'Customer/Client cancelled order',
                            customLabel: `CANCELLED - Customer/Client cancelled order`
                        },
                        {
                            id: '62',
                            label: 'Duplicate XML',
                            customLabel: `CANCELLED - Duplicate XML`
                        },
                        {
                            id: '63',
                            label: 'Returned and Cancelled',
                            customLabel: `CANCELLED - Returned and Cancelled`,
                            isDefaultExpanded: false,
                            children: [ 
                                {
                                    id: '630',
                                    label: 'No Response Received from Client/Customer',
                                    customLabel: `CANCELLED - Returned and Cancelled (No Response Received from Client/Customer)`,
                                },
                                {
                                    id: '631',
                                    label: 'Order Not Suitable for Redelivery',
                                    customLabel: `CANCELLED - Returned and Cancelled (Order Not Suitable for Redelivery)`,
                                },
                                // {
                                //     id: '632',
                                //     label: 'Redelivery Refused by Client/Customer',
                                //     customLabel: `CANCELLED - Returned and Cancelled (Redelivery Refused by Client/Customer)`,
                                // },
                                {
                                    id: '633',
                                    label: 'Multiple Redeliveries Attempted',
                                    customLabel: `CANCELLED - Returned and Cancelled (Multiple Redeliveries Attempted)`,
                                },
                                {
                                    id: '634',
                                    label: 'Other (Free Text)',
                                    customLabel: `CANCELLED - Returned and Cancelled (Other (Free Text))`,
                                    input: true,
                                },
                            ]
                        },
                        {
                            id: '64',
                            label: 'Product Out Of Stock',
                            customLabel: `CANCELLED - Product Out Of Stock`
                        },
                        {
                            id: '66',
                            label: 'Expiry date confirmation not received from customer',
                            customLabel: `CANCELLED - Expiry date confirmation not received from customer`
                        },
                        {
                            id: '67',
                            label: 'Shipping Address Undeliverable',
                            customLabel: `CANCELLED - Shipping Address Undeliverable`
                        },
                        {
                            id: '68',
                            label: 'Test Order',
                            customLabel: `CANCELLED - Test Order`
                        },                        
                        {
                            id: '69',
                            label: 'Other (Free Text)',
                            customLabel: `CANCELLED - Other`,
                            input: true,
                        },
                    ],
                },                
                {
                    id: '11',
                    label: 'CALL',
                },
                {
                    id: '16',
                    label: 'RETURNED',
                    isDefaultExpanded: false,
                    children: [
                        {
                            id: '160',
                            label: 'Multiple Deliveries Attempted by Courier',
                            customLabel: `RETURNED - Multiple Deliveries Attempted by Courier`
                        },
                        {
                            id: '161',
                            label: 'Incorrect/Incomplete Address',
                            customLabel: `RETURNED - Incorrect/Incomplete Address`
                        },
                        {
                            id: '162',
                            label: 'Customer Refused Delivery',
                            customLabel: `RETURNED - Customer Refused Delivery`
                        },
                        {
                            id: '163',
                            label: 'Shipment Not Collected by Customer',
                            customLabel: `RETURNED - Shipment Not Collected by Customer`
                        },
                        {
                            id: '164',
                            label: 'Other (Free Text)',
                            customLabel: `RETURNED - Other (Free Text)`,
                            input: true,
                        },
                    ],
                },                
            ];

           const orderStatusesSelect = [
                {
                    title: 'Select Prescription Status',
                    value: ''
                }, 
                {
                    title: 'SAFETYCHECK',
                    value: '9'
                },
                {
                    title: 'NEW',
                    value: '1'
                },
                {
                    title: 'APPROVED',
                    value: '2'
                },
                {
                    title: 'AWAITINGSHIPPED',
                    value: '7'
                },
                {
                    title: 'SHIPPED',
                    value: '8'
                },
                {
                    title: 'ONHOLD',
                    value: '10'
                },
                {
                    title: 'QUERIED',
                    value: '4'
                },
                {
                    title: 'QUERIEDDISPENSED',
                    value: '12'
                },
                {
                    title: 'QUERIEDNOTDISPENSED',
                    value: '13'
                },
                {
                    title: 'QUERIEDNOREPLY',
                    value: '14'
                },
                {
                    title: 'QUERIEDARCHIVED',
                    value: '15'
                },
                {
                    title: 'REJECTED',
                    value: '3'
                },
                {
                    title: 'CANCELLED',
                    value: '6'
                },
                {
                    title: 'POSTPONED',
                    value: '5'
                },
                {
                    title: 'CALL',
                    value: '11'
                },
                {
                    title: 'RETURN',
                    value: '16'
                },                
            ];
        
        const prescription = ref(undefined);
        const routeName = ref('');

        const orderStatusesOptionsComputed = computed(() => {
            let filteredOptions = [...orderStatusesOptions];
            if (prescription.value !== undefined) {
                if (prescription.value.Status === 9) {
                    filteredOptions = filteredOptions.filter(option => ['1', '6'].includes(option.id));

                    if (prescription.value.SubStatus === 91) {
                        filteredOptions.push({
                            id: '9',
                            label: 'SAFETYCHECK',
                            isDefaultExpanded: false,
                            children: [
                                {
                                    id: '91',
                                    label: 'Product Name Mismatch',
                                    customLabel: `SAFETYCHECK - Product Name Mismatch`
                                }
                            ]
                        });
                    } else if (prescription.value.SubStatus === 92) {
                        filteredOptions.push({
                            id: '9',
                            label: 'SAFETYCHECK',
                            isDefaultExpanded: false,
                            children: [
                                {
                                    id: '92',
                                    label: 'Formulation Mismatch',
                                    customLabel: `SAFETYCHECK - Formulation Mismatch`
                                }
                            ]
                        });
                    } else {
                        filteredOptions.push({
                            id: '9',
                            label: 'SAFETYCHECK',
                        });
                    }
                }
            }

            if (routeName.value === 'reports') {
                filteredOptions.splice(9, 0, {
                    id: '15',
                    label: 'QUERIEDARCHIVED',
                });

                filteredOptions.splice(9, 0, {
                    id: '14',
                    label: 'QUERIEDNOREPLY',
                });
            }

            return filteredOptions;
        });

        return {
            orderStatuses,
            orderSubStatuses,
            orderStatusesOptions,
            orderStatusesSelect,
            prescription,
            routeName,
            orderStatusesOptionsComputed,
        };
    },
};
