## Validation
A simple validation component
repository has:
    1. basic valid rule
    2. validation client
#### Usage
```php
    $validation = new \Validation\Validator([
        'name' => 'foo',
        'company' => 'bar',
        'type' => 1,
        'count' => 10
    ]);
    $validation->setRules([
        'name' => 'requried|max:8',
        'company' => 'min:3',
        'type' => 'required|in:1,2',
        'count' => new \Validation\Rule\WhenThen(
            'type',
            function ($v) {return $v == 1;},
            new \Validation\Rule\CallableRule('maxCount', function ($v) {
                return $v < 100;
            })
        )
    ]);

    $result = $validation->validate();

    print_r($validation->getErrors());
```

#### Rules
1. confirm
    one field must equal to another field
    ```php
    $validation->setRule('foo', 'confirm:bar');
    ``` 
2. file
    file limit
    ```php
    $validation->setRule('file', new File(1024*1024*5, 0, null,  true)); 
    ```
3. in
    field value contain in list
    ```php
    $validation->setRule('bar', 'in:1,2,3');
    ```
4. max
    field length must less than max value
    ```php
    $validation->setRule('bar', 'max:10');
    ```
5. numeric
    field must be a numeric
    ```php
    $validation->setRule('size', 'numeric');
    ```
6. requried
    field must be a required
    ```php
    $validation->setRule('name', 'required');
    ```
7. whenThen
    field valid when closure return true
    ```php
    $validation->setRule('days', new WhenThen('rememberMe', function ($v) {return $v == 'yes';}, 'in:1,3,7'));
    ```
8. callable rule
    callback rule, see CallableRule class
    ```php
    $validation->setRule('agree', function ($v) {
        if ($v == false) {
            $this->setError('You must agree the agreement');
            return false;
        }
        
        return true;
    });
    ```

#### Custom messages
    $validation->setRule('foo', 'required|in:1,2', [
        'required' => 'you miss foo',
        'in' => 'foo value must in [1,2]'
    ]);

#### extends your rule
you just need implements Validation\Rule\Rule
```php
class MyRule implements Validation\Rule\Rule
{
    public function getMessage()
    {
        return 'myRuleName';
    }

    public function pass($v, Validation\Validator $context)
    {
        return $v == 'ok';
    }

    public function getMessage()
    {
        return '%s is not equal to "ok"';
    }
}
```

#### TODO
1. more baisc rules
2. message with i18n