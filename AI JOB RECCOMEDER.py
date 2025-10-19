import streamlit as st
import pdfplumber
from docx import Document
import easyocr
import spacy
import re


nlp = spacy.load("en_core_web_sm")


TECH_KEYWORDS = [
    "python","java","c++","c#","javascript","html","css","sql","django",
    "flask","react","node.js","git","aws","azure","docker","kubernetes",
    "machine learning","deep learning","data analysis","pandas","numpy",
    "tensorflow","pytorch","scikit-learn","opencv","matplotlib","seaborn"
]

def extract_text(file):
    text = ""
    if file.name.endswith('.pdf'):
        with pdfplumber.open(file) as pdf:
            for page in pdf.pages:
                page_text = page.extract_text()
                if page_text:
                    text += page_text + "\n"
    elif file.name.endswith('.docx'):
        doc = Document(file)
        for para in doc.paragraphs:
            text += para.text + "\n"
    elif file.name.lower().endswith(('.png', '.jpg', '.jpeg')):
        reader = easyocr.Reader(['en'])
        result = reader.readtext(file.read())
        for detection in result:
            text += detection[1] + "\n"
    else:
        st.error("Unsupported file format")
    return text

def extract_skills(text):
    text = text.lower()
    doc = nlp(text)
    
    candidate_skills = set()
    for chunk in doc.noun_chunks:
        candidate_skills.add(chunk.text.strip())
    
    for token in doc:
        if token.pos_ in ["PROPN", "NOUN"]:
            candidate_skills.add(token.text.strip())
    
    extracted_skills = set()
    for skill in candidate_skills:
        for keyword in TECH_KEYWORDS:
            if re.search(rf'\b{re.escape(keyword)}\b', skill, re.IGNORECASE):
                extracted_skills.add(keyword)
    
    return list(extracted_skills)


st.title("📝 Resume Skill Extractor")

uploaded_file = st.file_uploader("Upload your resume (PDF, DOCX, JPG/PNG):", 
                                 type=["pdf", "docx", "jpg", "jpeg", "png"])

if uploaded_file:
    with st.spinner("Extracting text from resume..."):
        text = extract_text(uploaded_file)
    
    st.subheader("📄 Resume Text (Preview)")
    st.text_area("Resume Content", value=text[:1000] + "\n...", height=300)
    
    with st.spinner("Extracting skills..."):
        skills = extract_skills(text)
    
    st.subheader("💡 Extracted Skills")
    if skills:
        st.write(", ".join(skills))
    else:
        st.write("No technical skills detected.")
