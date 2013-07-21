Random thoughts
===============

- No basic auth: it means the browser caches the password and sends it with 
  every request, which is 'less' secure
- With the exception of public and config folder, which SHOULD be as small as 
  possible, completely object oriented 
- PSR-0, PSR-1 compliant. PSR-2 as well, except for the requirement not to use
  underscores for private and protected properies and methods. Please tell me if
  I accidently don't adhere to these. Not adhering to the underscore rule is 
  deliberate: it is meant to prevent confusion with magic methods, but they have
  double underscores, so it doesn't make sense (to me).
- I should really write a better README.md
