---
id: 83
title: "Upgrade to PHPUnit 12.5 in 7 diffs"
perex: |
    PHPUnit 12 was released a year ago, but only PHPUnit 12.5 released in December 2025 includes valuable features that are worth the once a year ugprade.

    The most important change, that will affect your code, is that mocks are now much more strict. And there also stubs... a mock that does not nothing. How to spot them and separate them?
---

What is difference between a mock and a stub? You didn't have to care untill PHPUnit 12.5, but now you do.

PHPUnit now complains miss-use verboselly, and there is no way to ignore it:

<img src="/assets/images/blog/2026/phpunit-notices-spam.png" class="img-thumbnail" style="max-width: 20em">

There is much more complex definition in the PHPUnit docs, but in plain English:

* **a mock** is a fake class, that has expectations of being called or not being called,

```php
$someMock = $this->createMock(SomeClass::class);
$someMock->expects($this->once())->method('someMethod')->willReturn(100);
```

We expect the `someMethod` to be called, or PHPUnit will crash.

* **a stub** is also a fake class, but it doesn't do anything at all.

```php
$someClass = new SomeClass($this->createStub(SomeDependency::class));
```

We can use it to make comply with constructor requirements

```php
$request = $this->createStub(Request::class);
$requestStack = new RequestStack($request);

$this->assertSame($request, $requestStack->getCurrentRequest());
```

We can also use it to assert the same object is used on a getter call later.

<br>

This leads us to first simplest change we can do.

<br>

## 1. Use `createStub()` over `createMock()`, where no expectations

```diff

```




