# #1 Building Entities Using Setters or Constructor Methods?

This question came up at the beginning of the construction of the `Weapon` entity, where things got confusing when the `setBaseDamage`
method returned an instance of `Weapon`, but other setters like `setName` returned an `AqwItem`. This introduces strange behavior 
to entities derived from `AqwItem`.

On the other hand, using a constructor to build these entities made the constructor methods require many parameters, 
which adds a layer of difficulty when constructing classes that extend from abstractions like `Weapon`.

But in the middle of this, I understood something important: when I use constructor methods, I ensure that an entity has all 
the properties that define it. I don’t get this guarantee when using setter methods.

Currently, all systems in the game are immutable and do not have behaviors like “what happens when I set the name of an item.”
An item is immutable in the end, so I don’t need a setter method for the name.

> Conclusion: Let's use constructor methods only. That way, we ensure immutability and cohesion.
