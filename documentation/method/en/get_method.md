Your contribution's needed!

Please update this page through GitHub using this standard format.

### Reference
`get_method($foo, $bar)`

**Parameter**
* **$foo** [`string`] *the detail related to the variable;*
* **$bar** [`string`] *the detail related to the variable.*

&nbsp;

### Usage Sample
`$this->get_method('foo', 'bar');`

`$this->get_method('baz', 'qux');`

**You can use this method in groups as below:**
```php
$this->get_method([
    'foo' => 'bar',
    'baz' => 'qux'
]);
```

&nbsp;

### Read Also
* [set_method](./set_method)