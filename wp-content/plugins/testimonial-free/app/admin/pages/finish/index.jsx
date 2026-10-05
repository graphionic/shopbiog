import axios from "axios";
import { __ } from "@wordpress/i18n";
import { useState } from "@wordpress/element";
import { useSelect } from "@wordpress/data";
import { CheckboxControl, Spinner } from "@wordpress/components";
import { UserDataInfoModal } from "../../setup-wizard/TemplateParts";
import { toastErrorMsg } from "../../functions";
import { STORE_NAME } from "../../store";

const FinishPage = () => {
	const allowAnonymousData = sp_real_admin_dashboard_localize?.dashboardInfo?.eap_allow_anonymous_data;
	const initialConsent = allowAnonymousData === undefined || allowAnonymousData === null ? true : allowAnonymousData;
	const [shareData, setShareData] = useState(initialConsent);
	const [isOpenModal, setOpenModal] = useState(false);
	const [isLoading, setIsLoading] = useState(false);

	const saveUserData = async () => {
		setIsLoading(true);
		try {
			const queryData = { shareData };
			const formData = new FormData();
			formData.append("action", "sp_real_get_user_consent");
			formData.append("nonce", sp_real_admin_dashboard_localize.nonce);
			formData.append("queryData", JSON.stringify(queryData));
			await axios.post(sp_real_admin_dashboard_localize?.ajax_url, formData);
			return { success: true };
		} catch (error) {
			toastErrorMsg(__("Something Went Wrong", "testimonial-free"));
			return { success: false, error };
		} finally {
			setIsLoading(false);
		}
	};

	const { pluginUrl, homeUrl } = useSelect((select) => select(STORE_NAME).getDashboardInfo(), []);
	const dashboardUrl = `${homeUrl}wp-admin/admin.php?page=rtp_dashboard`;
	const handleFinishSetupWizard = async () => {
		const result = await saveUserData();
		if (result && result.success) {
			window.location.href = dashboardUrl;
		}
	};

	return (
		<div className="sp-real-setup-finish-page sp-d-flex sp-flex-col sp-align-center sp-justify-center">
			<img src={`${pluginUrl}src/Admin/assets/images/setup-wizard/congratulations.svg`} alt="Congratulations" />
			<h3 className="sp-real-setup-page-title">
				{__("You’re Ready to Start Adding Testimonials", "testimonial-free")}
			</h3>
			<p className="sp-real-setup-page-desc" style={{ width: "636px", textAlign: "center" }}>
				{__("Add your first testimonial and start building your showcase. It only takes", "testimonial-free")}
				<b>{__(" less than 30 seconds,", "testimonial-free")}</b>
				{__(" and you can customize everything as you go.", "testimonial-free")}
			</p>
			<div className="sp-real-setup-wizard-action-btn sp-d-flex sp-align-center">
				<button
					className="sp-real-setup-wizard-nav-btn next-btn sp-real-setup-wizard-finish"
					onClick={handleFinishSetupWizard}
					disabled={isLoading}
					aria-busy={isLoading}
				>
					{isLoading ? (
						<>
							<Spinner /> {__("Finishing…", "testimonial-free")}
						</>
					) : (
						__("Add Your First Testimonial", "testimonial-free")
					)}
				</button>
				<a href={dashboardUrl} className="sp-real-setup-wizard-nav-btn dashboard-nav-btn">
					{__("Go to Dashboard", "testimonial-free")}
				</a>
			</div>
			<div className="sp-real-setup-finish-page-banner sp-d-flex sp-align-center sp-justify-between">
				<img
					src={`${pluginUrl}src/Admin/assets/images/setup-wizard/finish-page-img-01.png`}
					alt="finish-page-banner"
				/>
				<div className="sp-real-setup-finish-page-banner-content sp-d-flex sp-flex-col sp-align-center">
					<h3 className="sp-real-setup-page-title sp-text-center">
						{__("Over 225+ Ready Patterns to Match", "testimonial-free")}
						<br />
						{__("Every Style and Mood", "testimonial-free")}
					</h3>
					<a
						className="sp-real-setup-wizard-nav-btn prev-btn"
						href="https://realtestimonials.io/patterns/"
						target="_blank"
						rel="noreferrer"
					>
						{__("Explore all Patterns", "testimonial-free")}
					</a>
				</div>
				<img
					src={`${pluginUrl}src/Admin/assets/images/setup-wizard/finish-page-img-02.png`}
					alt="finish-page-banner"
				/>
			</div>
			<div className="spl-weather-checkbox-component-wrapper sp-d-flex sp-align-center">
				<p className="sp-real-setup-page-desc sp-d-flex sp-align-center sp-gap-4px">
					{__(
						"Help us improve Real Testimonials and get useful tips by sharing non-sensitive diagnostic data. See ",
						"testimonial-free"
					)}
					<span
						className="sp-real-modal-btn"
						style={{
							fontWeight: "600",
							textDecoration: "underline",
						}}
						onClick={() => setOpenModal(true)}
					>
						{__("what we collect.", "testimonial-free")}
					</span>
				</p>
				<CheckboxControl
					checked={shareData}
					onChange={() => setShareData(!shareData)}
					__nextHasNoMarginBottom
				/>
			</div>
			{isOpenModal && <UserDataInfoModal closeModal={() => setOpenModal(false)} />}
		</div>
	);
};

export default FinishPage;
