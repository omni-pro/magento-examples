# Module Omnipro_DeferredExample

This module provide a example for deferred operations in PHP for Magento framework

## Documentation

The example provide in this module consists in launch request to (https://httpstat.us/) with a sleep to service, in the sync mode the time for operation is summary all request processed, but in the async mode the time is equal to time one request because all request is launched at same time.

The controller for run example is {BASE_URL}/deferred/example/index?mode=async. The modes available is sync and async, for each response sended by controller is possible see time for execution.

This module is based in
- [Async operation Magento Developer](https://developer.adobe.com/commerce/php/development/components/async-operations/)

## Authors

- [@danyel-omni](https://github.com/danyel-omni)