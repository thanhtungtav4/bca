import React from "react";

const COMPANY = ["About us", "Our team", "Projects", "Career"];
const SERVICES = ["Mergers & Acquisitions", "Strategy", "Market Entry", "Capital Raising", "Research"];

function FLink({ children }) {
  return (
    <a
      href="#"
      style={{ fontFamily: "var(--font-sans)", fontWeight: 400, fontSize: 14, lineHeight: "26px", color: "rgba(255,255,255,0.72)", textDecoration: "none", transition: "var(--transition)" }}
      onMouseOver={(e) => (e.currentTarget.style.color = "#fff")}
      onMouseOut={(e) => (e.currentTarget.style.color = "rgba(255,255,255,0.72)")}
    >
      {children}
    </a>
  );
}

function Col({ title, children }) {
  return (
    <div style={{ display: "flex", flexDirection: "column", gap: 12, minWidth: 150 }}>
      <span style={{ fontFamily: "var(--font-sans)", fontWeight: 700, fontSize: 14, letterSpacing: "0.04em", textTransform: "uppercase", color: "var(--bca-white)" }}>{title}</span>
      <div style={{ display: "flex", flexDirection: "column" }}>{children}</div>
    </div>
  );
}

/**
 * Black site footer: brand + tagline, Company & Services link columns, contact
 * details, LinkedIn, and a bottom legal bar.
 */
export function Footer({
  links = COMPANY,
  services = SERVICES,
  address = "Unit G2, FOSCO Building, 06 Phung Khac Khoan\nDakao Ward, District 1, HCMC, Vietnam",
  tel = "+84 2835124414",
  email = "info@bcapartners.com.vn",
  style = {},
}) {
  return (
    <footer style={{ background: "var(--bca-black)", color: "var(--bca-white)", padding: "64px 112px 0", boxSizing: "border-box", ...style }}>
      <div style={{ display: "flex", flexWrap: "wrap", gap: 48, justifyContent: "space-between", alignItems: "flex-start" }}>
        <div style={{ display: "flex", flexDirection: "column", gap: 20, maxWidth: 320 }}>
          <span style={{ fontFamily: "var(--font-sans)", fontWeight: 600, fontSize: 24, lineHeight: "28px", color: "var(--bca-blue)" }}>BCA Partners</span>
          <p style={{ margin: 0, fontFamily: "var(--font-sans)", fontWeight: 400, fontSize: 14, lineHeight: "22px", color: "rgba(255,255,255,0.72)" }}>
            A Vietnam-based consulting firm for M&amp;A, strategy, corporate restructuring and market entry.
          </p>
          <a href="#" aria-label="LinkedIn" style={{ width: 40, height: 40, borderRadius: "var(--radius-pill)", background: "rgba(255,255,255,0.14)", display: "flex", alignItems: "center", justifyContent: "center", color: "var(--bca-white)", transition: "var(--transition)" }}
            onMouseOver={(e) => (e.currentTarget.style.background = "var(--bca-blue)")}
            onMouseOut={(e) => (e.currentTarget.style.background = "rgba(255,255,255,0.14)")}>
            <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
              <path d="M4.98 3.5a2.5 2.5 0 11.02 5 2.5 2.5 0 01-.02-5zM3 9h4v12H3zM9 9h3.8v1.7h.05c.53-1 1.83-2.05 3.77-2.05C20.4 8.65 21 11 21 14.1V21h-4v-6.1c0-1.45-.03-3.3-2-3.3-2 0-2.3 1.57-2.3 3.2V21H9z" />
            </svg>
          </a>
        </div>
        <Col title="Company">{links.map((l) => <FLink key={l}>{l}</FLink>)}</Col>
        <Col title="Services">{services.map((l) => <FLink key={l}>{l}</FLink>)}</Col>
        <Col title="Get in touch">
          <p style={{ margin: "0 0 12px", fontFamily: "var(--font-sans)", fontWeight: 400, fontSize: 14, lineHeight: "22px", color: "rgba(255,255,255,0.72)", whiteSpace: "pre-line", maxWidth: 260 }}>{address}</p>
          <a href={`tel:${tel.replace(/\s/g, "")}`} style={{ fontSize: 14, lineHeight: "24px", color: "rgba(255,255,255,0.72)", textDecoration: "none" }}>Tel: {tel}</a>
          <a href={`mailto:${email}`} style={{ fontSize: 14, lineHeight: "24px", color: "var(--bca-blue)", textDecoration: "none" }}>{email}</a>
        </Col>
      </div>
      <div style={{ marginTop: 48, padding: "20px 0", borderTop: "1px solid rgba(255,255,255,0.14)", display: "flex", flexWrap: "wrap", gap: 16, justifyContent: "space-between", alignItems: "center" }}>
        <span style={{ fontFamily: "var(--font-sans)", fontSize: 13, color: "rgba(255,255,255,0.55)" }}>© {new Date().getFullYear()} BCA Partners. All rights reserved.</span>
        <div style={{ display: "flex", gap: 24 }}><FLink>Privacy Policy</FLink><FLink>Terms of Use</FLink></div>
      </div>
    </footer>
  );
}
