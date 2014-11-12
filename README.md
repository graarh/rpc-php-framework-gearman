Computation cloud framework on php
==================================

Computation cloud, remote procedure calls, background tasks processing on php using gearman.
RabbitMQ, Memcached, Redis support is in the roadmap.

Framework core allows to switch between background systems without code changes. Start from simple php 
scripts that exchanges data over Redis. And replace it with gearman, or even storm then you need more power.

Example of usage
----------------

Currently, you can check tests for example of usage. Later on you can also look at the Examples directory.

Roadmap
-------

1. Make MultiTask more usable
2. Add an example
3. Make gearman test automatic
4. Update readme
5. Setup travis ci
6. Make documentation