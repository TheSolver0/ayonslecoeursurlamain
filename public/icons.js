(()=>{
 const icons={
  '↗':'<path d="M7 17 17 7M8 7h9v9"/>',
  '←':'<path d="m15 18-6-6 6-6M9 12h10"/>',
  '→':'<path d="m9 18 6-6-6-6M5 12h10"/>',
  '↑':'<path d="m6 10 6-6 6 6M12 4v16"/>',
  '↓':'<path d="m6 14 6 6 6-6M12 20V4"/>',
  '✓':'<path d="m5 12 4 4L19 6"/>',
  '♡':'<path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1.1-1.1a5.5 5.5 0 0 0-7.8 7.8l1.1 1.1L12 21l7.8-7.5 1.1-1.1a5.5 5.5 0 0 0-.1-7.8Z"/>',
  '✳':'<path d="M12 3v18M3 12h18M5.6 5.6l12.8 12.8M18.4 5.6 5.6 18.4"/>',
  '❋':'<path d="M12 12c-4-5-4-8-1.5-9.5C13 4 13 8 12 12Zm0 0c5-4 8-4 9.5-1.5C20 13 16 13 12 12Zm0 0c4 5 4 8 1.5 9.5C11 20 11 16 12 12Zm0 0c-5 4-8 4-9.5 1.5C4 11 8 11 12 12Z"/>',
  '⌂':'<path d="m3 11 9-8 9 8M5 10v10h14V10M9 20v-6h6v6"/>',
  '▧':'<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M8 4v16M8 9h13"/>',
  '▤':'<rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 8h10M7 12h10M7 16h7"/>',
  '◈':'<path d="m12 2 9 10-9 10L3 12 12 2Z"/><path d="m12 7 4.5 5-4.5 5-4.5-5 4.5-5Z"/>',
  '⚙':'<circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.7 1.7 0 0 0 .3 1.9l.1.1-2.8 2.8-.1-.1a1.7 1.7 0 0 0-1.9-.3 1.7 1.7 0 0 0-1 1.6v.2h-4V21a1.7 1.7 0 0 0-1-1.6 1.7 1.7 0 0 0-1.9.3l-.1.1L4.2 17l.1-.1a1.7 1.7 0 0 0 .3-1.9A1.7 1.7 0 0 0 3 14H2.8v-4H3a1.7 1.7 0 0 0 1.6-1 1.7 1.7 0 0 0-.3-1.9L4.2 7 7 4.2l.1.1A1.7 1.7 0 0 0 9 4.6a1.7 1.7 0 0 0 1-1.6v-.2h4V3a1.7 1.7 0 0 0 1 1.6 1.7 1.7 0 0 0 1.9-.3l.1-.1L19.8 7l-.1.1a1.7 1.7 0 0 0-.3 1.9 1.7 1.7 0 0 0 1.6 1h.2v4H21a1.7 1.7 0 0 0-1.6 1Z"/>',
  '☰':'<path d="M4 7h16M4 12h16M4 17h16"/>',
  '×':'<path d="m6 6 12 12M18 6 6 18"/>'
 };
 const pattern=new RegExp('(['+Object.keys(icons).join('')+'])','g');
 const skip=new Set(['SCRIPT','STYLE','TEXTAREA','INPUT','OPTION']);
 function svg(symbol){const el=document.createElementNS('http://www.w3.org/2000/svg','svg');el.setAttribute('class','ui-icon');el.setAttribute('viewBox','0 0 24 24');el.setAttribute('aria-hidden','true');el.innerHTML=icons[symbol];return el}
 function upgrade(root){
  if(root.nodeType===Node.TEXT_NODE){if(!root.parentElement||skip.has(root.parentElement.tagName)||!pattern.test(root.data)){pattern.lastIndex=0;return}pattern.lastIndex=0;const fragment=document.createDocumentFragment();root.data.split(pattern).forEach(part=>fragment.append(part in icons?svg(part):document.createTextNode(part)));root.replaceWith(fragment);return}
  if(root.nodeType!==Node.ELEMENT_NODE||skip.has(root.tagName)||root.closest('script,style,textarea,input,option'))return;
  const walker=document.createTreeWalker(root,NodeFilter.SHOW_TEXT);const nodes=[];while(walker.nextNode())nodes.push(walker.currentNode);nodes.forEach(upgrade)
 }
 upgrade(document.body);
 new MutationObserver(records=>records.forEach(record=>record.addedNodes.forEach(upgrade))).observe(document.body,{childList:true,subtree:true});
})();
