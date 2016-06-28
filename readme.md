##Social Network
is a social network with realtime chatting functionality using server sent events events , its back-end built with core PHP and front-end with html and a css frame work called Materialize CSS

since i couldn't integerate emojis in the chatting i have created some chat codes that can bold,italicise,underline and change the color of a particular part of your message. These codes start with an opening piece`*o`
then a combination of characters are used to mention one of the four operations `b for **bold**` `u for *underline*` `i for *italics*`

to specify color letter 'c' is followed by either one of the following letters `y=yellow`,`o=orange`,`b=black`,`p=pink`,then a `*` is added to the end of the opening piece to to indicate the end of the opening piece, to specify the part of message a closing piece is added after that particualr part, a closing piece is simply 2 c's written in between \*'s 
`*cc*`

for example
> message: this is the part which is \*oibcy\*yellow bold italics and underlined\*cc\*

the part `yellow bold italics and underlined` of the message will be displayed as bold, italicised and underlined and its color will be yellow in the chat window

###remaining features
- [ ] Online and offline status showing
- [ ] Email account verification and password resetting

