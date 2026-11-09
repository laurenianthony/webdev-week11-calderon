# WebDev Week 11: Advanced Query Optimization & PostgreSQL Indexing

## 📊 Query Performance Comparison

| Approach / Technique | Query Count (50 Events) | Notes / Behavior |
| :--- | :--- | :--- |
| **Naive (N+1 Problem)** | **101 Queries** | Makes 1 query for the events, plus 50 extra queries for venues and another 50 for participant counts. |
| **Fixed (Eager Loading)** | **2 Queries** | Fetches everything cleanly in bulk using `with('venue')` and `withCount('participants')`. |

## 🔍 Indexing & Execution Plans (`EXPLAIN ANALYZE`)

| Query Type | Without Index | With GIN Index |
| :--- | :--- | :--- |
| **JSONB Filter (`dress_code`)** | Sequential scan (had to check all 20,000+ rows) | Uses the GIN index for a much faster bitmap/index scan |
| **Full-Text Search (`quantum`)** | Sequential scan computing `to_tsvector` on every row | Uses the functional GIN index directly |

---

## 📝 Lab Assessment Written Answers

### Step 8: What impact does the N+1 problem have on web application scalability, and how does `Model::preventLazyLoading()` safeguard production environments?
The N+1 problem causes queries to scale poorly ($1 + N$) based on how many parent records are fetched, which quickly bogs down the database and spikes latency. `Model::preventLazyLoading()` acts as a safety check during development by throwing a `LazyLoadingViolationException` if an un-eager-loaded relationship gets accessed accidentally, making it easy to catch and fix before code ever reaches production.

### Step 9: Why are PostgreSQL GIN (Generalized Inverted Index) structures ideal for JSONB and Full-Text Search compared to standard B-Tree indexes?
Standard B-Tree indexes are built for basic scalar lookups like simple equality or ranges. JSONB data and full-text search involve nested structures, arrays, and tokenized text vectors. GIN indexes map those internal keys and word elements directly back to their rows, making containment checks (`@>`) and text matching way faster across large datasets.

---

## 💡 What I Learned

This week, I got a much better handle on how query performance bottlenecks work under the hood. Moving past basic ORM queries to look at actual execution plans made a big difference. I saw firsthand how lazy loading ruins performance and how simple it is to fix using eager loading (`with` and `withCount`). Working with PostgreSQL's JSONB operators and full-text search (`to_tsvector` / `plainto_tsquery`) also showed me how to handle unstructured data cleanly without tanking database speed.

One thing I'd like to explore further is how to fine-tune custom functional expressions with composite GIN indexes for more complex, multi-column JSONB filtering under heavy loads.