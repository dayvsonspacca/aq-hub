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

# #2 Control Error Flow Using Exceptions or Result Methods?

This question came up when I noticed that a `UseCase` can result in many exceptions—an exception when building an item, 
or another when using some service. This introduces a break in the `Input -> Process -> Output` flow of a `UseCase`,
and creates the need to add many `try-catch` blocks within the `UseCase`.

On the other hand, using Result methods protects the `UseCase` logic, but now I need to check `isSuccess` on all Result objects.

Looking at these two options, I decided to use the Result methods. This choice was made entirely to avoid breaking the `UseCase` flow.

> Conclusion: Let's use Result methods. That way, once again, we ensure immutability, cohesion, and a consistent process flow.

# #3 How Will Unit Tests Be Done?

Currently, I’m already writing unit tests, but I decided to define a standard for how these tests will be structured. 
They will follow these simple rules:

1. **Test if you can create an instance of a class.**  
2. **Test if the class can store and retrieve data correctly.**  
3. **Force errors and test how they are handled.**
