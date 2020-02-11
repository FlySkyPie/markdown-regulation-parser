Markdown Regulation Parser
===
Parse markdown format of regulation to JSON, made managed regulation more easy.

Markdown-like Regulation
---
This parser can only parse certain syntax of markdown.

### Level 1 Heading
To define name of regulation,
reused this syntax was useless,
second one would be ignore.

```markdown
# Name
```
or
```markdown
Name
===
```
### Level 3 Heading
To define chapters, if necessary.
```markdown
### Chapter
```

### Level 6 Heading
To record legislative of regulation.
```markdown
###### Promulgated on May 30, 1997.
###### Amendment to Article 2, Article 10 and Article 11, and deletion of Article 20, promulgated on May 17, 2000.
```

### Ordered or Unordered Lists
To descript article, paragraph, subsection and item,
those different kind of list are not different in interpret stage,
indent be more important to sperated which kind of stucture in regulation, just liked python. To make a indent, 1 tab character are equal to 4 spaces .

```markdown
1. This is article 1.
	- This is a paragraph.
		1. This is a subsection.
```
