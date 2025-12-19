# #1 Building Entities Using Setters or Constructor Methods? 2025-04-25

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

# #2 Control Error Flow Using Exceptions or Result Methods? 2025-05-06

This question came up when I noticed that a `UseCase` can result in many exceptions—an exception when building an item, 
or another when using some service. This introduces a break in the `Input -> Process -> Output` flow of a `UseCase`,
and creates the need to add many `try-catch` blocks within the `UseCase`.

On the other hand, using `Result` methods protects the `UseCase` logic, but now I need to check `isSuccess` on all Result objects.

Looking at these two options, I decided to use the `Result` methods. This choice was made entirely to avoid breaking the `UseCase` flow.

> Conclusion: Let's use `Result` methods. That way, once again, we ensure immutability, cohesion, and a consistent process flow.

# #3 How Will Unit Tests Be Done? 2025-05-08

Currently, I’m already writing unit tests, but I decided to define a standard for how these tests will be structured. 
They will follow these simple rules:

1. **Test if you can create an instance of a class.**  
2. **Test if the class can store and retrieve data correctly.**  
3. **Force errors and test how they are handled.**

# #4 Should Repository Methods Always Return a Result? 2025-09-23  

This question came up while building the Repository classes, which are responsible for performing operations in the database.  
Suppose I want to persist a new record, and this function should return an identifier.  

The doubt is: should this operation always return a `Result`, like `Result<Identifier>`?  
After all, something can go wrong during persistence: the database may be down, or a record with a unique key may already exist.  

Wrapping the return type in a `Result` gives a consistent behavior:  
- `Result<Identifier>` in case of success.  
- `Result<null>` in case of failure.

In my mind, this approach makes sense, as it maintains immutability, cohesion, and a uniform way of handling errors across all layers.  

> Conclusion: Repository methods should always return a `Result`. That way, we guarantee consistency and reliability when dealing with database operations.  

# #5 Why Do We Need an Identifier to Persist a Player? 2025-09-23  

While working on the Player persistence layer, the question arose: why exactly do we need an identifier for a Player?  

After some investigation, I realized that the only reliable representation available for now is the user ID from AQW itself.  
So instead of generating a new internal ID for each Player, I decided to reuse the same AQW user ID. 

> Conclusion: The Player’s identifier will be the AQW user ID. This avoids unnecessary duplication and keeps the system aligned with its external reference.

# #6 Should We Enforce Item Uniqueness by Name, or Something Else?  
2025-09-24  

While working on the persistence of `Items` like `Weapon` or `Armor`, I faced a critical question: should we enforce that two items with the same name cannot exist in the system? At first, this sounded like a reasonable rule, since the name feels like the most natural identifier.  

However, I quickly ran into a problem. In AQW, there are cases where two completely different items share the exact same name. One example is the [*Burning Blade*](http://aqwwiki.wikidot.com/burning-blade) in reality, there are at least two distinct items with this name, differing in appearance, rarity, and attributes.  

This breaks the assumption that the **name** could serve as a unique key. If names are unreliable, then the question becomes: which parameter can truly define the uniqueness of an item when persisting it? Unlike the Player case, where I could rely on the external AQW user ID, I haven’t yet discovered what the equivalent unique identifier for `Items` is.

Given that, I am considering creating an **internal unique identifier** for items in the system. Each AQW item in my system is represented by an `ItemInfo` object, which includes the `Name`, `Description`, `ItemTags`, and **The Item Type** (`Weapon` or `Armor`) of the item. By combining these fields into a single string and generating a hash from it, I could produce a deterministic identifier that is unique for every distinct item.  

This approach would allow anyone with the same `ItemInfo` to generate the same identifier, ensuring consistency while avoiding collisions between items that share a name but differ in other attributes.

> Conclusion Since item names are not guaranteed to be unique in AQW, we will not rely on them to enforce uniqueness. Instead, we will create a deterministic internal identifier by combining key attributes of an item (`Name`, `Description`, `ItemTags`, and **The Item Type Classname**) and generating a hash from them. This ensures that each distinct item can be uniquely identified and consistently persisted, even when multiple items share the same name.

# #7 How should repositories consistently return data across the system?
2025-09-26

Previously, different repositories returned raw entities, making it difficult to reason about data flow between layers.

To solve this, I decided to adopt **DataObjects** as the standard **DTO (Data Transfer Object)** for all repositories. Each repository will now return a `Data` object that explicitly represents the entity and its data, providing a clear contract for consumers.

This approach brings several benefits:
- **Consistency:** Every repository has a predictable output structure.
- **Clarity:** The system clearly specifies what data is being transferred between layers.
- **Flexibility:** Changes to internal entity structures don’t directly impact consumers, as the `DataObject` serves as a stable interface.

> Conclusion: All repositories will return `DataObjects` as DTOs, defining a consistent way for data to leave the repository layer and ensuring clarity and safety across the system.

# #8 Where Will the Initial Data for the System Come From?  
2025-12-19  

This question arose during the definition of the data acquisition strategy. Currently, the system needs a reliable source of information to populate its database and become functional as a wiki-style resource.  

While the ultimate goal is to extract high-fidelity data directly from the game's infrastructure using the tools developed in [dayvsonspacca/aqw-miner](https://github.com/dayvsonspacca/aqw-miner), implementing and refining these specialized miners takes time. To ensure a usable version of the API can be launched sooner, a more immediate approach is required.  

The AQW Wiki, despite not having all the technical parameters I eventually want to store, provides a structured and comprehensive base of items.  

> **Conclusion:** For now, the system's data will come exclusively from **Web Scraping the official AQW Wiki**. This allows for a faster initial release of the API. The integration of more precise data directly from the game via [aqw-miner](https://github.com/dayvsonspacca/aqw-miner) will be treated as a future enhancement to enrich the existing records.